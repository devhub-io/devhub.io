@extends('layouts.front')

@section('contents')
<section id="topics">
    <div class="container">
        <div class="row">
            <h1 class="wow fadeInUp text-center">@lang('front.topic')</h1>

            <div class="row top-50" data-wow-delay=".3s" data-effect="mfp-zoom-in">
                @foreach($collections as $item)
                <div class="col-md-4 center">
                    <a href="{{ l_url('collection', [$item->slug]) }}">
                        <img src="{{ $item->image ? cdn_asset($item->image) : cdn_asset('img/270x270.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" width="270" height="270" class="lazyload">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="guess">
    <div class="container">
        <div class="row">
            <h1 class="wow fadeInUp text-center">Guess you like it</h1>

            <div class="row top-50" data-wow-delay=".3s" data-effect="mfp-zoom-in">
                @foreach($recommend as $item)
                <div class="col-md-2 center guess-item">
                    <a href="{{ l_url('repos', [$item->slug]) }}">
                        <img src="{{ $item->cover ? $item->cover . '&s=200' : cdn_asset('img/210x269.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" width="200" class="lazyload">
                        <p>{{ $item->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="top">
    <div class="container">
        <h1>@lang('front.popular')</h1>
        @foreach($hot as $item)
            <a href="{{ l_url('repos', [$item->slug]) }}">
                <div class="row">
                    <div class="col-md-3 title">{{ $item->owner }}/{{ $item->repo }}</div>
                    <div class="col-md-6 desc">{{ $item->description  }}</div>
                    <div class="col-md-2 col-md-offset-1 stars"><i class="fa fa-star"></i> {{ $item->stargazers_count }} <span class="line">{{ $item->trends }}</span></div>
                </div>
            </a>
        @endforeach
        <h1>@lang('front.latest')</h1>
        @foreach($new as $item)
            <a href="{{ l_url('repos', [$item->slug]) }}">
                <div class="row">
                    <div class="col-md-3 title">{{ $item->owner }}/{{ $item->repo }}</div>
                    <div class="col-md-6 desc">{{ $item->description  }}</div>
                    <div class="col-md-2 col-md-offset-1 stars"><i class="fa fa-star"></i> {{ $item->stargazers_count }} <span class="line">{{ $item->trends }}</span></div>
                </div>
            </a>
        @endforeach
        <h1>@lang('front.trend')</h1>
        @foreach($trend as $item)
            <a href="{{ l_url('repos', [$item->slug]) }}">
                <div class="row">
                    <div class="col-md-3 title">{{ $item->owner }}/{{ $item->repo }}</div>
                    <div class="col-md-6 desc">{{ $item->description  }}</div>
                    <div class="col-md-2 col-md-offset-1 stars"><i class="fa fa-star"></i> {{ $item->stargazers_count }} <span class="line">{{ $item->trends }}</span></div>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section id="subscribe">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="subtitle"></p>
                <h2>Subscribe our newsletters</h2>
                <form action="">
                    <div class="form-group">
                        <input type="email" placeholder="Email here">
                        <a href="#" class="btn btn-green">Subscribe</a>
                    </div>
                </form>
                <p class="promise">We promise to never spam you.</p>
            </div>
        </div>
    </div>
</section><!-- //Subscribe -->
@endsection
