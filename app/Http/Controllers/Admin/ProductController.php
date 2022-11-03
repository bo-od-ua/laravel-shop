<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCatalogRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    private $imageSaver;

    public function __construct(ImageSaver $imageSaver){
        $this->imageSaver= $imageSaver;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roots= Category::where('parent_id', 0)->get();
        $products= Product::paginate(5);
        return view('admin.product.index', compact('products', 'roots'));
    }

    public function category(Category $category){
        $products= $category->products()->paginate(5);
        return view('admin.product.category', compact('category', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items= Category::all();
        $brands= Brand::all();
        return view('admin.product.create', compact('items', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductCatalogRequest $request)
    {
        $request->merge([
            'new'=>  $request->has('new'),
            'hit'=>  $request->has('hit'),
            'sale'=> $request->has('sale'),
        ]);
        $data= $request->all();
        $data['image']= $this->imageSaver->upload($request, null, 'product');
        $product= Product::create($data);
        return redirect()
            ->route('admin.product.show', ['product'=> $product->id])
            ->with('success', 'Новый товар успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $items= Product::all();
        $brands= Brand::all();
        return view('admin.product.edit', compact('product', 'items', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductCatalogRequest $request, Product $product)
    {
        $request->merge([
            'new'=>  $request->has('new'),
            'hit'=>  $request->has('hit'),
            'sale'=> $request->has('sale'),
        ]);
        $data= $request->all();
        $data['image']= $this->imageSaver->upload($request, $product, 'product');
        $product->update($data);
        return redirect()
            ->route('admin.product.show', ['product', $product->id])
            ->with('success', 'Товар был успешно обновлён');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $this->imageSaver->remove($product, 'product');
        $product->delete();
        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Товар успешно удалён');
    }
}
