@extends('layouts.front')

@section('contents')
<div class="maincontent-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="latest-product">
                    <div class="section-title"></div>
                    <div class="product-carousel">
                        @foreach($recommend as $item)
                        <div class="single-product">
                            <div class="product-f-image">
                                <img data-src="{{ $item->image > 0 ? image_url($item->image, ['w' => 210]) : cdn_asset('img/210x269.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload">
                                <div class="product-hover">
                                    <a href="{{ l_url('repos', [$item->slug]) }}" class="view-details-link"><i class="fa fa-link"></i> @lang('front.see_details')</a>
                                </div>
                            </div>
                            <h2><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h2>
                            <div class="product-carousel-price">
                                <div></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End main content area -->

<div class="brands-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="brand-wrapper">
                    <h2 class="section-title">@lang('front.topic')</h2>
                    <div class="brand-list">
                        @foreach($collections as $item)
                            <a href="{{ l_url('collection', [$item->slug]) }}">
                                <img data-src="{{ $item->image ? cdn_asset($item->image) : cdn_asset('img/270x270.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" width="270" height="270" class="lazyload">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End brands area -->

<div class="product-widget-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">@lang('front.popular')</h2>
                    <a href="" class="wid-view-more">@lang('front.view_all')</a>
                    @foreach($hot as $item)
                    <div class="single-wid-product">
                        <a href="{{ l_url('repos', [$item->slug]) }}"><img data-src="{{ $item->image > 0 ? image_url($item->image, ['w' => 100]) : cdn_asset('img/100x90.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="product-thumb lazyload"></a>
                        <h2><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h2>
                        <div class="product-wid-rating">
                            <i class="fa fa-star"></i> {{ $item->stargazers_count }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">@lang('front.latest')</h2>
                    <a href="#" class="wid-view-more">@lang('front.view_all')</a>
                    @foreach($new as $item)
                        <div class="single-wid-product">
                            <a href="{{ l_url('repos', [$item->slug]) }}"><img data-src="{{ $item->image > 0 ? image_url($item->image, ['w' => 100]) : cdn_asset('img/100x90.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="product-thumb lazyload"></a>
                            <h2><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i> {{ $item->stargazers_count }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">@lang('front.trend')</h2>
                    <a href="#" class="wid-view-more">@lang('front.view_all')</a>
                    @foreach($trend as $item)
                        <div class="single-wid-product">
                            <a href="{{ l_url('repos', [$item->slug]) }}"><img data-src="{{ $item->image > 0 ? image_url($item->image, ['w' => 100]) : cdn_asset('img/100x90.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="product-thumb lazyload"></a>
                            <h2><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i> {{ $item->stargazers_count }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> <!-- End product widget area -->
@endsection
