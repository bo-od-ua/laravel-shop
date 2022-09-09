@extends('layout.site')

@section('content')
<h1>Категория {{$brand->name}}</h1>
<p>{{ $brand->content }}</p>

<div class="row">
    @foreach ($brand->products as $product)
        @include('catalog.patrial.product', ['product' => $product])
    @endforeach
</div>
@endsection
