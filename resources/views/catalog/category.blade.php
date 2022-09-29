@extends('layout.site')

@section('content')
<h1>Категория {{$category->name}}</h1>
<div class="row">
    @foreach ($category->products as $product)
        @include('catalog.part.product', ['product' => $product])
    @endforeach
</div>
@endsection
