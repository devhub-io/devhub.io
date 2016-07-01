@extends('layouts.front')

@section('contents')
    <div class="container">
        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-4 col-sm-4 hidden-xs">
                <img class="cover" alt="100%x200" data-src="holder.js/100%x200"
                     style="height: 200px; width: 100%; display: block;"
                     src="{{ image_url($repos->image, ['h' => 200]) }}"
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
            <div class="col-md-12">
                {!! $markdown !!}
            </div>
        </div>
    </div>

@endsection
