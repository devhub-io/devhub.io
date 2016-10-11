@extends('layouts.front')

@section('styles')
    <style>
        .breadcrumb {
            margin-top: 25px;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        {!! Breadcrumbs::render('developer', $developer) !!}

        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-3 col-sm-4 hidden-xs">
                <img class="cover" src="{{ $developer->avatar_url ? $developer->avatar_url : cdn_asset('img/300x300.png') }}" alt="{{ $developer->login }}" title="{{ $developer->login }}">
            </div>
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $developer->name }} <span>({{ $developer->login }})</span>
                    </h1>
                </div>
                <div class="menu hidden-xs" style="margin-bottom: 10px;">
                    @if($developer->blog)
                        <a target="_blank" href="{{ link_url($developer->blog) }}" rel="nofollow"><i class="fa fa-home fa-2x"></i> @lang('front.homepage') </a> &nbsp;&nbsp;
                    @endif
                    <a target="_blank" href="{{ $developer->html_url }}" class="gitbtn" rel="nofollow"><i class="fa fa-github fa-2x"></i> Github </a> &nbsp;&nbsp;
                </div>
                <div class="params hidden-xs">
                    <div style="margin-bottom: 10px;">
                        <a aria-label="Follow @{{ $developer->login }} on GitHub" data-count-aria-label="# followers on GitHub" data-count-api="/users/{{ $developer->login }}#followers" data-count-href="/{{ $developer->login }}/followers" href="https://github.com/{{ $developer->login }}" class="github-button">Follow</a>
                    </div>
                    <div title="@lang('front.last_updated')">
                        <i class="fa fa-clock-o"></i> <span>{{ $developer->site_updated_at }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <article class="col-md-12">
                @if($owner_repos->count() > 0)
                    <h2>Repository</h2>
                    <div class="row">
                        @foreach($owner_repos as $item)
                            <div class="col-md-3">
                                <div class="thumbnail" style="height: 150px;">
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h4>{{ $item->title }}</h4></a>
                                        <div style="margin-bottom: 10px">
                                            <span title="star">
                                                <i class="glyphicon glyphicon-star"></i> {{ $item->stargazers_count }}
                                            </span>
                                            <span class="line">{{ $item->trends }}</span>
                                        </div>
                                        <p>{{ mb_substr($item->description, 0, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($contribute_repos->count() > 0)
                    <h2>Contribute</h2>
                    <div class="row">
                        @foreach($contribute_repos as $item)
                            <div class="col-md-3">
                                <div class="thumbnail" style="height: 362px;">
                                    <a href="{{ l_url('repos', [$item->repos->slug]) }}"><img src="{{ $item->repos->image > 0 ? image_url($item->repos->image, ['h' => 200]) : ($item->repos->cover ? $item->repos->cover : cdn_asset('img/200x200.png')) }}" alt="{{ $item->repos->title }}" title="{{ $item->repos->title }}" class="lazyload" width="200"></a>
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h4>{{ $item->repos->title }}</h4></a>
                                        <div style="margin-bottom: 10px;">
                                            <span title="star">
                                                <i class="glyphicon glyphicon-star"></i> {{ $item->repos->stargazers_count }}
                                            </span>
                                            <span class="line">{{ $item->repos->trends }}</span>
                                        </div>
                                        <p>{{ mb_substr($item->repos->description, 0, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        </div>
    </div>
@endsection

@section('scripts')
    <script async defer src="https://buttons.github.io/buttons.js"></script>
@endsection
