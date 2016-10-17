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
                <img class="cover" src="{{ $repos->cover ? $repos->cover : cdn_asset('img/300x300.png') }}" alt="{{ $repos->title }}" title="{{ $repos->title }}" width="300">
            </div>
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $repos->title }} <span class="line" title="Trends">{{ $repos->trends }}</span>
                        @foreach($repos->badges as $badge)
                            @if($badge->url)
                                <a href="{{ $badge->url }}" rel="nofollow" target="_blank" title="{{ $badge->name }} {{ $badge->type == 'package' ? 'package' : '' }}" style="text-decoration: none">
                                    <img src="{{ badge_image_url($badge->name) }}" alt="{{ $badge->name }} {{ $badge->type == 'package' ? 'package' : '' }}" width="20" height="20">
                                </a>
                            @else
                                <img src="{{ badge_image_url($badge->name) }}" alt="{{ $badge->name }} {{ $badge->type == 'package' ? 'package' : '' }}" title="{{ $badge->name }} {{ $badge->type == 'package' ? 'package' : '' }}" width="20" height="20">
                            @endif
                        @endforeach
                    </h1>
                    <p>{{ $repos->description }}</p>
                </div>
                <div class="menu hidden-xs" style="margin-bottom: 10px;">
                    @if($repos->homepage)
                    <a target="_blank" href="{{ link_url($repos->homepage) }}" rel="nofollow"><i class="fa fa-home fa-2x"></i> @lang('front.homepage') </a> &nbsp;&nbsp;
                    @endif
                    @if($repos->github)
                    <a target="_blank" href="{{ link_url($repos->github) }}" class="gitbtn" rel="nofollow"><i class="fa fa-github fa-2x"></i> Github </a> &nbsp;&nbsp;
                    @endif
                    <a target="_blank" href="{{ link_url(stackoverflow_tagged_url($tag)) }}" class="gitbtn" rel="nofollow"><i class="fa fa-stack-overflow fa-2x"></i> Questions </a>
                </div>
                <div class="params hidden-xs">
                    <div style="margin-bottom: 10px;">
                        <a aria-label="Star {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# stargazers on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#stargazers_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/stargazers" data-icon="octicon-star" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}" class="github-button">Star</a>
                        <a aria-label="Fork {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# forks on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#forks_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/network" data-icon="octicon-repo-forked" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/fork" class="github-button">Fork</a>
                        <a aria-label="Watch {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# watchers on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#subscribers_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/watchers" data-icon="octicon-eye" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}" class="github-button">Watch</a>
                        <a aria-label="Issue {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# issues on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#open_issues_count" data-icon="octicon-issue-opened" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/issues" class="github-button">Issue</a>
                        <a aria-label="Download {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-icon="octicon-cloud-download" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/archive/master.zip" class="github-button">Download</a>
                    </div>
                    <div style="margin-bottom: 10px;" title="@lang('front.last_updated')">
                        <i class="fa fa-clock-o"></i> <span>{{ $repos->repos_updated_at }}</span>
                    </div>
                    <div>
                        @if($repos->license)
                            <a href="https://spdx.org/licenses/{{ $repos->license->spdx_id }}.html" target="_blank" rel="nofollow" title="{{ $repos->license->name }}"><span class="label label-info">{{ $repos->license->spdx_id }}</span></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                @if($languages)
                    <canvas id="languages" height="160" width="160"></canvas>
                    <h5 style="text-align: center">Languages</h5>
                @endif
            </div>
        </div>
        <div class="row">
            <article class="col-md-8 markdown-body">
                {!! $markdown !!}
            </article>

            <div class="col-md-4" style="margin-bottom: 50px">
                @if($related_repos->count() > 0)
                    <h3>Related Repositories</h3>
                    @foreach($related_repos as $item)
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">
                                <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->cover ? $item->cover : cdn_asset('img/200x200.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="100"></a>
                            </div>
                            <div class="col-md-8">
                                <h4><a href="{{ l_url('repos', [$item->slug]) }}">{{ $item->title }}</a></h4>
                                <p>{{ mb_substr($item->description, 0, 80) }} ...</p>
                            </div>
                        </div>
                    @endforeach
                @endif

                <br>

                @if($repos->contributors->count() > 0)
                    <h3>Top Contributors</h3>
                    <div>
                        @foreach($repos->contributors as $contributor)
                            <a href="{{ l_url('developer', [$contributor->login]) }}" target="_blank">
                                <img src="{{ $contributor->avatar_url }}" alt="{{ $contributor->login }}" title="{{ $contributor->login }}" class="pull-left" width="60" height="60">
                            </a>
                        @endforeach
                    </div>
                    <div style="clear: both"></div>
                @endif

                <br>

                @if($repos->tags->count() > 0)
                    <h3>Releases</h3>
                    <div>
                        @foreach($repos->tags as $tag)
                            <div>-&nbsp;&nbsp; {{ $tag->name }} <a href="{{ $tag->zipball_url }}" rel="nofollow"><i class="fa fa-file-archive-o"> zip</i></a> <a href="{{ $tag->tarball_url }}" rel="nofollow"><i class="fa fa-file-archive-o"> tar</i></a></div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
    <script>
        var options = {
            legend: false,
            responsive: false
        };

        new Chart(document.getElementById("languages"), {
            type: 'doughnut',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: {
                labels: develophub.languages_labels,
                datasets: [{
                    data: develophub.languages_values,
                    backgroundColor: develophub.languages_color,
                    hoverBackgroundColor: develophub.languages_color
                }]
            },
            options: options
        });
    </script>
@endsection
