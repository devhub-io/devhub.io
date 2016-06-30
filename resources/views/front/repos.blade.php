@extends('layouts.front')

@section('contents')
    <div class="container">
        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-4 col-sm-4 hidden-xs">
                <img class="cover" alt="100%x200" data-src="holder.js/100%x200"
                     style="height: 200px; width: 100%; display: block;"
                     src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU1OTc3ZWIxOTAgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTU5NzdlYjE5MCI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OC44NDk5OTg0NzQxMjExIiB5PSIxMDUuNyI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg=="
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
