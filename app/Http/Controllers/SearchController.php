<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('products.index');
        }
        
        // Get products with fuzzy search
        $products = $this->searchProducts($query);
        
        return view('search.results', compact('products', 'query'));
    }
    
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Search products with fuzzy matching
        $products = $this->searchProducts($query, 10);
        
        $results = [];
        
        foreach ($products as $product) {
            // Build category breadcrumb
            $breadcrumb = $this->getCategoryBreadcrumb($product->category);
            
            $results[] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image' => $product->image,
                'category' => $product->category->name,
                'breadcrumb' => $breadcrumb,
                'url' => route('products.show', $product->slug)
            ];
        }
        
        // Also search categories
        $categories = $this->searchCategories($query, 5);
        
        foreach ($categories as $category) {
            $breadcrumb = $this->getCategoryBreadcrumb($category);
            
            $results[] = [
                'id' => 'cat_' . $category->id,
                'name' => $category->name,
                'type' => 'category',
                'breadcrumb' => $breadcrumb,
                'url' => route('categories.show', $category->slug),
                'product_count' => $category->products()->count()
            ];
        }
        
        return response()->json($results);
    }
    
    private function searchProducts($query, $limit = null)
    {
        $query = strtolower($query);
        
        // Split query into words for better matching
        $words = explode(' ', $query);
        
        $productsQuery = Product::where('is_active', true)
            ->with(['category.parent']);
        
        // Search in product name and description
        $productsQuery->where(function ($q) use ($words, $query) {
            // Exact match gets priority
            $q->where('name', 'LIKE', '%' . $query . '%')
              ->orWhere('description', 'LIKE', '%' . $query . '%');
            
            // Then fuzzy match each word
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $q->orWhere('name', 'LIKE', '%' . $word . '%')
                      ->orWhere('description', 'LIKE', '%' . $word . '%');
                    
                    // Soundex matching for spelling mistakes
                    $q->orWhereRaw("SOUNDEX(name) = SOUNDEX(?)", [$word]);
                    
                    // Levenshtein distance for typos (MySQL specific)
                    // For PostgreSQL, use different function
                    if (strlen($word) > 3) {
                        $patterns = $this->generateTypoPatterns($word);
                        foreach ($patterns as $pattern) {
                            $q->orWhere('name', 'LIKE', '%' . $pattern . '%');
                        }
                    }
                }
            }
            
            // Search in category names
            $q->orWhereHas('category', function ($catQuery) use ($query, $words) {
                $catQuery->where('name', 'LIKE', '%' . $query . '%');
                foreach ($words as $word) {
                    if (strlen($word) > 2) {
                        $catQuery->orWhere('name', 'LIKE', '%' . $word . '%');
                    }
                }
            });
        });
        
        // Order by relevance
        $productsQuery->orderByRaw("
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN name LIKE ? THEN 3
                ELSE 4
            END
        ", [$query . '%', '%' . $query . '%', '%' . $query]);
        
        if ($limit) {
            $productsQuery->limit($limit);
        }
        
        return $productsQuery->get();
    }
    
    private function searchCategories($query, $limit = 5)
    {
        $query = strtolower($query);
        
        return Category::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%');
                
                // Soundex matching
                $q->orWhereRaw("SOUNDEX(name) = SOUNDEX(?)", [$query]);
            })
            ->with('parent')
            ->limit($limit)
            ->get();
    }
    
    private function getCategoryBreadcrumb($category)
    {
        $breadcrumb = [];
        $current = $category;
        
        while ($current) {
            array_unshift($breadcrumb, $current->name);
            $current = $current->parent;
        }
        
        return implode(' → ', $breadcrumb);
    }
    
    private function generateTypoPatterns($word)
    {
        $patterns = [];
        $length = strlen($word);
        
        // Common typo patterns
        for ($i = 0; $i < $length - 1; $i++) {
            // Swap adjacent characters
            $swapped = $word;
            $swapped[$i] = $word[$i + 1];
            $swapped[$i + 1] = $word[$i];
            $patterns[] = $swapped;
        }
        
        // Missing characters
        for ($i = 0; $i < $length; $i++) {
            $patterns[] = substr($word, 0, $i) . substr($word, $i + 1);
        }
        
        // Common replacements
        $replacements = [
            'ph' => 'f',
            'f' => 'ph',
            'i' => 'e',
            'e' => 'i',
            'o' => '0',
            '0' => 'o',
        ];
        
        foreach ($replacements as $from => $to) {
            if (strpos($word, $from) !== false) {
                $patterns[] = str_replace($from, $to, $word);
            }
        }
        
        return array_unique($patterns);
    }
}