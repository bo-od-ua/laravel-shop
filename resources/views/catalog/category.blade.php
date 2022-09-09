@extends('layout.site')

@section('content')
<h1>Категория {{$category->name}}</h1>
<div class="row">
    @foreach ($products as $product)
        @include('catalog.patrial.product', ['product' => $product])
    @endforeach
</div>
@endsection
