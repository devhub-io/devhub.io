@extends('layouts.front')

@section('styles')
    <style>
        .breadcrumb {
            margin-top: 25px;
        }

        #carbonads {
            display: block;
            overflow: hidden;
            margin: 0 auto;
            padding: 1em;
            max-width: 500px;
            border: 1px dashed #e6e6e6;
            font-size: 16px;
            line-height: 1.5;;
        }

        #carbonads a:hover {
            color: inherit;
            text-decoration: none;
        }

        #carbonads span {
            position: relative;
            display: block;
            overflow: hidden;
        }

        .carbon-img {
            float: left;
            margin-right: 1em;
        }

        .carbon-img img { display: block; }

        .carbon-text {
            display: block;
            float: left;
            max-width: calc(100% - 130px - 1em);
            text-align: left;
        }

        .carbon-poweredby {
            position: absolute;
            right: 0;
            bottom: 0;
            display: block;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        @include('widgets.breadcrumbs.developer', ['developer' => $developer])

        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-3 col-sm-4">
                <img class="cover" src="{{ $developer->avatar_url ? $developer->avatar_url : cdn_asset('img/300x300.png') }}" alt="{{ $developer->login }}" title="{{ $developer->login }}">
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="repo-title">
                    <h1>
                        @if($developer->type == 'Organization') <i class="fa fa-users" title="Organization"></i> @else <i class="fa fa-user" title="User"></i> @endif {{ $developer->name }} <span>({{ $developer->login }})</span>
                    </h1>
                </div>
                <div class="menu" style="margin-bottom: 10px;">
                    @if($developer->blog)
                        <a target="_blank" href="{{ link_url($developer->blog) }}" rel="nofollow" title="{{ $developer->blog }}"><i class="fa fa-home fa-2x"></i> @lang('front.homepage') </a> &nbsp;&nbsp;
                    @endif
                    <a target="_blank" href="{{ $developer->html_url }}" class="gitbtn" rel="nofollow"><i class="fa fa-github fa-2x"></i> Github </a> &nbsp;&nbsp;
                    @if($developer->public_gists > 0)
                        <a target="_blank" href="{{ link_url("https://gist.github.com/$developer->login") }}" class="gitbtn" rel="nofollow"><i class="fa fa-github-square fa-2x"></i> Github Gist </a> &nbsp;&nbsp;
                    @endif
                </div>
                <div class="params">
                    @if($developer->type == 'User')
                        <div style="margin-bottom: 10px;">
                            <a aria-label="Follow @{{ $developer->login }} on GitHub" data-count-aria-label="# followers on GitHub" data-count-api="/users/{{ $developer->login }}#followers" data-count-href="/{{ $developer->login }}/followers" href="https://github.com/{{ $developer->login }}" class="github-button">Follow</a>
                        </div>
                    @endif
                    <div title="@lang('front.last_updated')">
                        <i class="fa fa-clock-o"></i> <span>{{ Carbon\Carbon::now()->diffForHumans(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $developer->site_updated_at)) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <article class="col-md-12">


                <h2>About {{ $developer->name ? $developer->name : $developer->login }}</h2>
                <p>
                    Summing up all of {{ $developer->name ? $developer->name : $developer->login }}'s repositories they have {{ $owner_repos->count() }} own repositories @if($contribute_count > 0) and {{ $contribute_count }} contribute repositories @endif.
                </p>
                @if($developer->type == 'User')
                <p>
                    {{ $developer->name ? $developer->name : $developer->login }} follows {{ $developer->following }} other users and is followed by {{ $developer->followers }} users.
                </p>
                @endif
                <p>
                    Data for {{ $developer->name ? $developer->name : $developer->login }} was last updated {{ Carbon\Carbon::now()->diffForHumans(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $developer->site_updated_at)) }}.
                </p>
            </article>
        </div>
        <div class="row">
            <div class="col-md-12">
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

                @if($contribute_repos->count() > 0 && $has_contribute)
                    <h2>Contribute</h2>
                    <div class="row">
                        @foreach($contribute_repos as $item)
                            @if($item->repos)
                                <div class="col-md-3">
                                    <div class="thumbnail" style="height: 362px;">
                                        <a href="{{ l_url('repos', [$item->repos->slug]) }}"><img src="{{ $item->repos->cover ? $item->repos->cover : cdn_asset('img/200x200.png') }}" alt="{{ $item->repos->title }}" title="{{ $item->repos->title }}" class="lazyload" width="200"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->repos->slug]) }}"><h4>{{ $item->repos->title }}</h4></a>
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
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script async defer src="https://buttons.github.io/buttons.js"></script>
@endsection
