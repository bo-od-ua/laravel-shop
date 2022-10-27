@extends('layout.site', ['title' => $category->name])

@section('content')
<h1>Категория {{$category->name}}</h1>
<p>{{$category->content}}</p>
<div class="row">
    @foreach ($category->children as $child)
        @include('catalog.part.category', ['category' => $child])
    @endforeach
</div>
<h5 class="bg-info text-white p-2 mb-4">Товары раздела</h5>
<div class="row">
    @foreach ($category->products as $product)
        @include('catalog.part.product', ['product' => $product])
    @endforeach
</div>

@endsection
