@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <h1 class="text-center">Search</h1>
            <dvi class="row">
                <div class="col-md-12">
                    <form action="{{ l_url('search') }}" class="form">
                        <input  class="form-control" type="search" name="keyword" value="{{ $keyword }}" placeholder="@lang('front.search_placeholder')">
                    </form>
                </div>
            </dvi>
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-left">
                        <div class="row">
                            @foreach($repos as $item)
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail" style="height: 450px;">
                                        <a href="{{ l_url('repos', [$item->slug]) }}">
                                            <img src="{{ $item->cover ? $item->cover . '&s=300' : cdn_asset('img/300x300.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="300">
                                        </a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->owner }}/{{ $item->repo }}</h3></a>
                                            <span class="line">{{ $item->trends }}</span>&nbsp;&nbsp;<span title="Stargazers count"><i class="fa fa-star"></i> {{ $item->stargazers_count }}</span>
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
