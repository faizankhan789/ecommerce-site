<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories');
            $table->index('slug');
            $table->index('category_id');
            $table->index('is_featured');
            $table->index('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};