<?php

namespace App\Http\Controllers;

use App\Helpers\ProductFilter;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(){
        $roots= Category::where('parent_id', 0)->get();
        $brands= Brand::popular();
        return view('catalog.index', compact('roots', 'brands'));
    }

    public function category(Category $category, ProductFilter $filters)
    {
        $products= Product::categoryProducts($category->id)
            ->filterProducts($filters)
            ->paginate(6)
            ->withQueryString();

        return view('catalog.category', compact('category', 'products'));
    }

    public function brand(Brand $brand, ProductFilter $filters)
    {
        $products= $brand
            ->products()
            ->filterProducts($filters)
            ->paginate(6)
            ->withQueryString();
        return view('catalog.brand', compact('brand', 'products'));
    }

    public function product(Product $product)
    {
        return view('catalog.product', compact('product'));
    }

//    public function category($slug)
//    {
//        $category= Category::where('slug', $slug)->firstOrFail();
//        return view('catalog.category', compact('category'));
//    }
//
//    public function brand($slug)
//    {
//        $brand= Brand::where('slug', $slug)->firstOrFail();
//        return view('catalog.brand', compact('brand'));
//    }
//
//    public function product($slug)
//    {
//        $product= Product::where('slug', $slug)->firstOrFail();
//
//        return view('catalog.product', compact('product'));
//    }
}
