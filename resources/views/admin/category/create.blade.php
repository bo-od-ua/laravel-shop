@extends('layout.admin')

@section('content')
    <h1>Создание новой категории</h1>
    <form method="post" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
        @include('admin.category.part.form')
    </form>
@endsection
