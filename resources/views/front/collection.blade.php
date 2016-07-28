@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="sidebar-title" style="text-align: center;font-size: 36px;">{{ $collection->title }}</h1>
                </div>

                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="row">
                            @foreach($repos as $item)
                                <div class="col-sm-4 col-md-3">
                                    <div class="thumbnail" style="height: 362px;">
                                        <a href="{{ l_url('repos', [$item->repos->slug]) }}"><img src="{{ $item->repos->image > 0 ? image_url($item->repos->image, ['h' => 200]) : 'holder.js/200x200' }}" alt="{{ $item->repos->title }}" title="{{ $item->repos->title }}"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->repos->slug]) }}"><h3>{{ $item->repos->title }}</h3></a>
                                            <p>{{ $item->repos->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
