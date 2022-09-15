<h4>Разделы каталога</h4>
<ul>
@foreach($items as $item)
    <li>
        <a href="{{route('catalog.category', ['slug'=> $item->slug])}}">{{$item->name}}</a>
    </li>
@endforeach
</ul>
