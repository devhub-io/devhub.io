@extends('layouts.front')

@section('styles')
    <link rel="stylesheet" href="{{ cdn_asset('css/github-markdown.css') }}">
    <style>
        .markdown-body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
        }
        .breadcrumb {
            margin-top: 25px;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        {!! Breadcrumbs::render('repos', $repos) !!}

        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-3 col-sm-4 hidden-xs">
                <img class="cover" src="{{ $repos->image > 0 ? image_url($repos->image, ['w' => 300]) : ($repos->cover ? $repos->cover : cdn_asset('img/300x300.png')) }}" alt="{{ $repos->title }}" title="{{ $repos->title }}">
            </div>
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $repos->title }} <span class="line" title="Trends">{{ $repos->trends }}</span>
                        @foreach($repos->badges as $badge)
                            <img src="{{ badge_image_url($badge->name) }}" alt="{{ $badge->name }} package" title="{{ $badge->name }} package" width="20" height="20">
                        @endforeach
                    </h1>
                    <p>{{ $repos->description }}</p>
                </div>
                <div class="menu hidden-xs">
                    @if($repos->homepage)
                    <a target="_blank" href="{{ link_url($repos->homepage) }}" rel="nofollow"><i class="fa fa-home fa-2x"></i> @lang('front.homepage') </a>
                    @endif
                    @if($repos->github)
                    <a target="_blank" href="{{ link_url($repos->github) }}" class="gitbtn" rel="nofollow"><i class="fa fa-github fa-2x"></i> Github </a>
                    @endif
                    <a target="_blank" href="{{ link_url(stackoverflow_tagged_url($tag)) }}" class="gitbtn" rel="nofollow"><i class="fa fa-stack-overflow fa-2x"></i> Questions </a>
                </div>
                <div class="params hidden-xs">
                    <div style="border-left: 0" title="@lang('front.stargazers_count')">
                        <i class="fa fa-star"></i> <span>{{ $repos->stargazers_count }}</span>
                    </div>
                    <div title="@lang('front.last_updated')">
                        <i class="fa fa-clock-o"></i> <span>{{ $repos->repos_updated_at }}</span>
                    </div>
                    <div title="@lang('front.forks_count')">
                        <i class="fa fa-code-fork"></i> <span>{{ $repos->forks_count }}</span>
                    </div>
                    {{--<div title="issue 响应速度">--}}
                        {{--<i class="fa fa-exclamation-circle"></i> <span>{{ $repos-> }}</span>--}}
                    {{--</div>--}}
                </div>
            </div>
            <div class="col-md-2">
                <table class="table">
                    @foreach($languages as $language => $num)
                    <tr>
                        <td>{{ $language }}</td>
                        <td>{{ $num }}%</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <article class="col-md-8 markdown-body">
                {!! $markdown !!}
            </article>

            <div class="col-md-4">
                @if($repos->tags->count() > 0)
                    <h3>Releases</h3>
                    <div>
                        @foreach($repos->tags as $tag)
                            <div>-&nbsp;&nbsp; {{ $tag->name }} <a href="{{ $tag->zipball_url }}" rel="nofollow"><i class="fa fa-file-archive-o"> zip</i></a> <a href="{{ $tag->tarball_url }}" rel="nofollow"><i class="fa fa-file-archive-o"> tar</i></a></div>
                        @endforeach
                    </div>
                @endif

                <br>

                @if($related_repos->count() > 0)
                    <h3>Related Repositories</h3>
                    @foreach($related_repos as $item)
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">
                                <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->image > 0 ? image_url($item->image, ['h' => 100]) : ($item->cover ? $item->cover : cdn_asset('img/200x200.png')) }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="100"></a>
                            </div>
                            <div class="col-md-8">
                                <h4><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h4>
                                <p>{{ mb_substr($item->description, 0, 100) }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

                <br>

                @if($repos->contributors->count() > 0)
                    <h3>Top Contributors</h3>
                    @foreach($repos->contributors as $contributor)
                    <a href="{{ $contributor->html_url }}" target="_blank" rel="nofollow">
                        <img src="{{ $contributor->avatar_url }}" alt="{{ $contributor->login }}" class="pull-left" width="60" height="60">
                    </a>
                    @endforeach
                @endif

                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
