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

        #carbonads {
            border: 1px dashed #e6e6e6;
            display: block;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 1em;
            overflow: hidden;
            padding: 1em;
        }

        #carbonads img {
            margin-right: 1em;
        }

        #carbonads .carbon-poweredby {
            margin-left: 1em;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        @include('widgets.breadcrumbs.repos', ['repos' => $repos])

        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-3 col-sm-4 hidden-xs">
                <img class="cover" src="{{ $repos->cover ? $repos->cover : cdn_asset('img/300x300.png') }}" alt="{{ $repos->title }}" title="{{ $repos->title }}" width="300">
            </div>
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $repos->title }} <span class="line" title="Trends">{{ $repos->trends }}</span>
                        @foreach($analytics_badges as $badge)
                            @if($badge->url)
                                <a href="{{ link_url($badge->url) }}" rel="nofollow" target="_blank" title="{{ $badge->name }}" style="text-decoration: none">
                                    <img src="{{ badge_image_url($badge->name) }}" alt="{{ $badge->name }}" width="20" height="20">
                                </a>
                            @else
                                <img src="{{ badge_image_url($badge->name) }}" alt="{{ $badge->name }}" title="{{ $badge->name }}" width="20" height="20">
                            @endif
                        @endforeach
                    </h1>
                    <p>{{ $repos->description }}</p>
                </div>
                <div class="menu" style="margin-bottom: 10px;">
                    @if($repos->homepage)
                    <a target="_blank" href="{{ link_url($repos->homepage) }}" rel="nofollow" title="{{ $repos->homepage }}"><i class="fa fa-home fa-2x"></i> @lang('front.homepage') </a> &nbsp;&nbsp;
                    @endif
                    @if($repos->github)
                    <a target="_blank" href="{{ link_url($repos->github) }}" rel="nofollow"><i class="fa fa-github fa-2x"></i> Github </a> &nbsp;&nbsp;
                    @endif
                    @if($repos->have_questions)
                    <a target="_blank" href="{{ l_url("repos/$repos->slug/questions") }}"><i class="fa fa-stack-overflow fa-2x"></i> Questions </a> &nbsp;&nbsp;
                    @endif
                    @if($gitter_badge)
                    <a target="_blank" href="{{ link_url($gitter_badge->url) }}" rel="nofollow"><i class="fa fa-comments-o fa-2x"></i> Gitter </a> &nbsp;&nbsp;
                    @endif
                    @if($developer_exists)
                    <a href="{{ l_url('developer', [$repos->owner]) }}"><i class="fa fa-user fa-2x"></i> Developer</a> &nbsp;&nbsp;
                    @endif
                    @if($repos->document_url)
                    <a href="{{ link_url($repos->document_url) }}" rel="nofollow" target="_blank"><i class="fa fa-book fa-2x"></i> Documentation</a> &nbsp;&nbsp;
                    @endif
                    @if($news_exists)
                    <a target="_blank" href="{{ l_url("repos/$repos->slug/news") }}"><i class="fa fa-newspaper-o fa-2x"></i> News </a> &nbsp;&nbsp;
                    @endif
                </div>
                <div class="params">
                    <div style="margin-bottom: 10px;">
                        <a aria-label="Star {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# stargazers on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#stargazers_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/stargazers" data-icon="octicon-star" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}" class="github-button">Star</a>
                        <a aria-label="Fork {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# forks on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#forks_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/network" data-icon="octicon-repo-forked" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/fork" class="github-button">Fork</a>
                        <a aria-label="Watch {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# watchers on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#subscribers_count" data-count-href="/{{ $repos->owner }}/{{ $repos->repo }}/watchers" data-icon="octicon-eye" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}" class="github-button">Watch</a>
                        <a aria-label="Issue {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-count-aria-label="# issues on GitHub" data-count-api="/repos/{{ $repos->owner }}/{{ $repos->repo }}#open_issues_count" data-icon="octicon-issue-opened" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/issues" class="github-button">Issue</a>
                        <a aria-label="Download {{ $repos->owner }}/{{ $repos->repo }} on GitHub" data-icon="octicon-cloud-download" href="https://github.com/{{ $repos->owner }}/{{ $repos->repo }}/archive/master.zip" class="github-button">Download</a>
                    </div>
                    <div style="margin-bottom: 10px;" title="@lang('front.last_updated')">
                        <i class="fa fa-clock-o"></i> <span>{{ Carbon\Carbon::now()->diffForHumans(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $repos->repos_updated_at)) }}</span>
                        @if($repos->license)
                            <a href="{{ link_url("https://spdx.org/licenses/{$repos->license->spdx_id}.html") }}" target="_blank" rel="nofollow" title="{{ $repos->license->name }}" style="color: #333; text-decoration: none;"><i class="fa fa-copyright"></i> <span>{{ $repos->license->spdx_id }}</span></a>
                        @endif
                    </div>
                    @if($topics->count() > 0)
                        <div style="margin-bottom: 10px;">
                            @foreach($topics as $item)
                                <a class="label label-primary" style="display: inline-block;" href="{{ l_url('topic', [$item->topic]) }}">{{$item->topic}}</a>
                            @endforeach
                        </div>
                    @endif
                    <div>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#reviewModal">
                            I use {{ $repos->owner }}/{{ $repos->repo }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-2 hidden-xs">
                @if($languages)
                    <canvas id="languages" height="160" width="160"></canvas>
                    <h5 style="text-align: center">Languages</h5>
                @endif
            </div>
        </div>
        <div class="row">
            <article class="col-md-8 markdown-body">
                <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?zoneid=1673&serve=C6AILKT&placement=devhubio" id="_carbonads_js"></script>

                @foreach($packages as $package)
                    @include('widgets.packages.' . $package->provider, ['package' => $package])
                @endforeach

                {!! $markdown !!}
            </article>

            <div class="col-md-4" style="margin-bottom: 50px">
                @if($related_repos->count() > 0)
                    <h3>Related Repositories</h3>
                    @foreach($related_repos as $item)
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">
                                <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->cover ? $item->cover . '&s=100' : cdn_asset('img/200x200.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="100"></a>
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
                            <a href="{{ l_url('developer', [$contributor->login]) }}" target="_blank" rel="nofollow">
                                <img src="{{ $contributor->avatar_url . '&s=60' }}" alt="{{ $contributor->login }}" title="{{ $contributor->login }}" class="pull-left" width="60" height="60">
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

    <div aria-hidden="true" aria-labelledby="reviewModalLabel" role="dialog" tabindex="-1" id="reviewModal" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 id="mcqReviewModalLabel" class="modal-title">Would you tell us more about {{ $repos->owner }}/{{ $repos->repo }}?</h4>
                </div>
                <form style="padding:10px;" action="{{ l_url('repos/review') }}" method="POST" role="form" id="review-form" class="form-horizontal bv-form" novalidate="novalidate">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $repos->id }}" name="repos_id">
                    <div style="padding-top:0;" class="modal-body">
                        <div class="form-group mcq_input">
                            <h4>Is the project reliable?</h4>
                            <div class="input-group">
                                <div class="radio">
                                    <label><input type="radio" value="1" name="reliable">Yes, realiable</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="0" name="reliable">Somewhat realiable</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="-1" name="reliable">Not realiable</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mcq_input has-feedback">
                            <h4>Would you recommend this project?</h4>
                            <div class="input-group">
                                <div class="radio">
                                    <label><input type="radio" class="definitely_recommend" value="1" name="recommendation">Yes, definitely</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" class="no_recommend" value="0" name="recommendation">Not sure</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" class="no_recommend" value="-1" name="recommendation">Nope</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mcq_input">
                            <h4>Is the documentation helpful?</h4>
                            <div class="input-group">
                                <div class="radio">
                                    <label><input type="radio" value="1" name="documentation">Yes, helpful</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="0" name="documentation">Somewhat helpful</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="-1" name="documentation">Not that helpful</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                            <button class="btn btn-primary" type="submit" name="button">Submit</button>
                        </div>
                    </div>
                </form>
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
        window.develophub = JSON.parse('{!! $javascript_bind !!}');

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

        $('#review-form').submit(function () {
            if ($('#review-form input[name=reliable]:checked').val() === undefined) {
                return false;
            }
            if ($('#review-form input[name=recommendation]:checked').val() === undefined) {
                return false;
            }
            if ($('#review-form input[name=documentation]:checked').val() === undefined) {
                return false;
            }
            return true;
        });
        @if(session()->has('flash_notification.message'))
            alert('{{ session('flash_notification.message') }}');
        @endif
    </script>
@endsection
