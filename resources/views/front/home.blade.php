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
                                <img src="{{ image_url($item->image, ['w' => 430]) }}" alt="" width="430" height="550">
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
                        <img src="img/services_logo__1.jpg" alt="">
                        <img src="img/services_logo__2.jpg" alt="">
                        <img src="img/services_logo__3.jpg" alt="">
                        <img src="img/services_logo__4.jpg" alt="">
                        <img src="img/services_logo__1.jpg" alt="">
                        <img src="img/services_logo__2.jpg" alt="">
                        <img src="img/services_logo__3.jpg" alt="">
                        <img src="img/services_logo__4.jpg" alt="">
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
                        <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ image_url($item->image, ['w' => 100]) }}" alt="" class="product-thumb"></a>
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
                            <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ image_url($item->image, ['w' => 100]) }}" alt="" class="product-thumb"></a>
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
                            <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ image_url($item->image, ['w' => 100]) }}" alt="" class="product-thumb"></a>
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
