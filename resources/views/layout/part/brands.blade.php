<h4>Бренды</h4>
<ul>
    @foreach($items as $item)
        <li>
            {{-- <a href="{{route('catalog.brand', ['slug'=> $item->slug])}}">{{$item->name}}</a> --}}
            <a href="{{route('catalog.brand', [$item->slug])}}">{{$item->name}}</a>
            <span class="badge badge-dark flight-right">{{$item->products_count}}</span>
        </li>
    @endforeach
</ul>
