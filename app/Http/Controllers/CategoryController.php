<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('activeChildren')
            ->orderBy('order')
            ->get();
            
        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $query = Product::where('category_id', $category->id)
            ->where('is_active', true);
            
        // Apply sorting
        $sort = request('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
            
        return view('categories.show', compact('category', 'products'));
    }

    public function getSubcategories($parentId)
    {
        $subcategories = Category::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return response()->json($subcategories);
    }
}