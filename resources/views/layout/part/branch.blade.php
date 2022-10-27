<ul>
    @foreach($items->where('parent_id', $parent) as $item)
        <li>
           {{-- <a href="{{route('catalog.category', ['slug'=> $item->slug])}}">{{$item->name}}</a> --}} {{-- ['category'=> $item->slug]--}}
           <a href="{{route('catalog.category', [$item->slug])}}">{{$item->name}}</a>
            @if(count($items->where('parent_id', $item->id)))
                <span class="badge badge-dark">
                    <i class="fa fa-plus"></i>
                </span>
                @include('layout.part.branch', ['parent'=> $item->id])
            @endif
        </li>
    @endforeach
</ul>
