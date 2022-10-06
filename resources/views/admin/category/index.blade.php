@extends('layout.admin', ['title' => 'Все категории каталога'])

@section('content')
    <h1>Все категории</h1>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Наименование</th>
            <th width="65%">Описание</th>
            <th><i class="fas fa-edit"></i></th>
            <th><i class="fas fa-trash"></i></th>
        </tr>
        @include('admin.category.part.tree', ['items'=> $roots, 'level'=> -1])
    </table>
@endsection
