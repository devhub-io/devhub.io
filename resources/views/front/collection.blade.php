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
                                        <a href="{{ l_url('repos', [$item->repos->slug]) }}"><img src="{{ $item->repos->cover ? $item->repos->cover : cdn_asset('img/200x200.png') }}" alt="{{ $item->repos->title }}" title="{{ $item->repos->title }}" width="200"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->repos->slug]) }}"><h3>{{ $item->repos->title }}</h3></a>
                                            <span class="line">{{ $item->repos->trends }}</span>
                                            <p>{{ mb_substr($item->repos->description, 0, 100) }}</p>
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
