@extends('layouts.front')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/github-markdown.css') }}">
    <style>
        .markdown-body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-4 col-sm-4 hidden-xs">
                <img class="cover"
                     style="height: 300px; width: 100%; display: block;"
                     src="{{ $repos->image > 0 ? image_url($repos->image, ['w' => 300]) : 'holder.js/300x300' }}"
                     data-holder-rendered="true">
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $repos->title }}
                    </h1>
                    <p>
                        {{ $repos->description }}
                    </p>
                </div>
                <div class="menu hidden-xs">
                    @if($repos->homepage)
                    <a target="_blank" href="{{ $repos->homepage }}"><i class="fa fa-home"></i> @lang('front.homepage') </a>
                    @endif
                    @if($repos->github)
                    <a target="_blank" href="{{ $repos->github }}" class="gitbtn"><i class="fa fa-github"></i> Github </a>
                    @endif
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
        </div>
        <div class="row">
            <article class="col-md-12 markdown-body">
                {!! $markdown !!}
            </article>
        </div>
    </div>

@endsection
