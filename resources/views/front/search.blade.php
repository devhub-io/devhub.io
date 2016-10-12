@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-left">
                        <div class="row">
                            @foreach($repos as $item)
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail" style="height: 400px;">
                                        <a href="{{ l_url('repos', [$item->slug]) }}">
                                            <img src="{{ $item->cover ? $item->cover : cdn_asset('img/300x300.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="300"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->title }}</h3></a>
                                            <span class="line">{{ $item->trends }}</span>
                                            <p>{{ mb_substr($item->description, 0, 35) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{ $repos->appends(['keyword' => $keyword])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
