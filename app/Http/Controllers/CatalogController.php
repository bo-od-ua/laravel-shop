<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Brand;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(){
        $roots= Category::where('parent_id', 0)->get();
        return view('catalog.index', compact('roots'));
    }

    public function category($slug)
    {
        $category= Category::where('slug', $slug)->firstOrFail();
        $products= $category->getProducts();
        return view('catalog.category', compact('category','products'));
    }

    public function brand($slug)
    {
        $brand= Brand::where('slug', $slug)->firstOrFail();
        $products= $brand->getProducts();
        return view('catalog.brand', compact('brand','products'));
    }

    public function product($slug)
    {
        $product= Product::where('slug', $slug)->firstOrFail();

        $category= $product->getCategory();
        $brand= $product->getBrand();

        return view('catalog.product', compact('category', 'brand', 'product'));
    }
}
