<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages= Page::all();
        return view('admin.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents= Page::where('parent_id', 0)->get();
        return view('admin.page.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required|max:100',
            'parent_id'=> 'required|regex:~^[0-9]+$~',
            'slug'=> 'required|max:100|unique:pages|regex:~^[-_a-z0-9]+$~i',
            'content'=> 'required',
        ]);

        $page= Page::create($request->all());
        return redirect()
            ->route('admin.page.show', ['page'=> $page->id])
            ->with('success', 'Страница создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return view('admin.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $parents= Page::where('parent_id', 0)->get();
        return view('admin.page.edit', compact('page', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'name'=> 'required|max:100',
            'parent_id'=> 'required|regex:~^[0-9]+$~|not_in:'.$page->id,
            'slug'=> 'required|max:100|unique:pages,slug,'.$page->id.',id|regex:~^[-_a-z0-9]+$~i',
            'content'=> 'required',
        ]);

        $page= $page->update($request->all());
        return redirect()
            ->route('admin.page.show', ['page'=> $page->id])
            ->with('success', 'Страница сохранена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if($page->children->count()){
            return back()->withError('Сначала удалие дочерние страницы');
        }
        $this->removeImages($page->content);
        $page->delete();
        return redirect()
            ->route('admin.page.index')
            ->with('success', 'Страница удалена');
    }

    /**
     * Upload the image from WYSIWYG editor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function uploadImage(Request $request)
    {
        $validator= Validator::make($request->all(), ['image'=> [
            'mimes:jpeg,jpg,png',
            'max:5000'
        ]]);

        $path = $request->file('image')->store('page', 'public');
        $url = Storage::disk('public')->url($path);
        return response()->json(['image'=> $url]);
    }

    /**
     * delete image uploaded from WYSIWYG editor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function removeImage(Request $request)
    {
        $path= parse_url($request->image, PHP_URL_PATH);
        $path= str_replace('/storage/', '', $path);
        if(Storage::disk('public')->exists($path)){
            Storage::disk('public')->delete($path);
            return 'Изображение удалено';
        }
        return 'Не удалось удалить изображение';
    }

    private function removeImages($content){
        $dom= new \DomDocument();
        $dom->loadHTML($content);
        $images= $dom->getElementsByTagName('img');
        foreach($images as $img){
            $src= $img->getAttribute('src');
            $pattern= '~/storage/page/([0-9a-f]{32}\.(jpeg|png|gif))~';
            if(preg_match($pattern, $src, $match)){
                $name= $match[1];
                if(Storage::disk('public')->extends('page/'.$name)){
                    Storage::disk('public')->delete('page/'.$name);
                }
            }
        }
    }
}
