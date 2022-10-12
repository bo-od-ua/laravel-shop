<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCatalogRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    private $imageServer;

    public function __construct(ImageSaver $imageServer){
        $this->imageServer= $imageServer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items= Category::all();
        return view('admin.category.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items= Category::all();
        return view('admin.category.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCatalogRequest $request)
    {
        $data= $request->all();
        $data['image']= $this->imageServer->upload($request, null, 'category');

        $category= Category::create($data);
        return redirect()
            ->route('admin.category.show', ['category'=> $category->id])
            ->with('success', 'Новая категория создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $items= Category::all();
        return view('admin.category.edit', compact('category', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryCatalogRequest $request, Category $category)
    {
        $data= $request->all();
        $data['image']= $this->imageServer->upload($request, null, 'category');

        $category->update($data);

        return redirect()
            ->route('admin.category.show', ['category'=> $category->id])
            ->with('success', 'Категория успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->children->count()){
            $errors[]= 'Нельзя удалить категорию с дочерними категориями';
        }
        if($category->products->count()){
            $errors[]= 'Нельзя удалить категорию, которая содержит товары';
        }
        if(!empty($errors)){
            return back()->withErrors($errors);
        }

        $category->delete();
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория каталога успешно удалена');
    }
}
