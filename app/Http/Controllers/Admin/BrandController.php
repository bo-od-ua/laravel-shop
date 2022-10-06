<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private $imageSaver;

    function __construct(ImageSaver $imageSaver){
        $this->imageSaver= $imageSaver;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands= Brand::all();
        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data= $request->all();
        $data['image']= $this->imageSaver->upload($request, null, 'brand');
        $brand= Brand::create($data);
        return redirect()
            ->route('admin.brand.show', ['brand'=> $brand->id])
            ->with('success', 'Бренд создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $data= $request->all();
        $data['image']= $this->imageSaver->upload($request, null, 'brand');
        $brand= Brand::update($data);
        return redirect()
            ->route('admin.brand.show', ['brand'=> $brand->id])
            ->with('success', 'Бренд сохранен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if($brand->products->count()){
            return back()->withErrors('Ytkmpz elfkbnm ,htyl c njdfhfvb');
        }
        $this->imageSaver->remove($brand, 'brand');
        $brand->delete();
        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Бренд удален');
    }
}
