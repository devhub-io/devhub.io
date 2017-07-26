@extends('layouts.front')

@section('styles')
    <style>
        .breadcrumb {
            margin-top: 25px;
        }
    </style>
@endsection

@section('contents')
<section id="content">
    <div class="container">
        @include('widgets.breadcrumbs.news', ['date' => $current_date])

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="repo-title">
                    <h1>What's hot on Github on {{ $current_date }}</h1>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            @foreach($news as $item)
                <div class="col-md-12">
                    <div style="min-height: 120px;">
                        <div class="caption">
                            <a href="{{ link_url('https://news.ycombinator.com/item?id='.$item->item_id) }}" rel="nofollow" target="_blank" style="text-decoration: none;">
                                <h2><span class="label label-default">{{ $item->score }}</span> {{ $item->title }}</h2>
                            </a>
                            @if($item->repos)
                                <a style="font-size: 16px;" href="{{ l_url('repos', [$item->repos->slug]) }}" target="_blank" style="text-decoration: none;">
                                    <i class="fa fa-github"></i>
                                    {{ $item->repos->owner }}/{{ $item->repos->repo }}  &nbsp;&nbsp;

                                    <span title="Stargazers count">
                                        <i class="glyphicon glyphicon-star"></i> {{ $item->repos->stargazers_count }} &nbsp;&nbsp;
                                    </span>
                                </a>
                                <p>{{ $item->repos->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row" style="font-size: 23px">
            @if($prev)
                <div class="col-md-2">
                    <a class="label label-info" href="{{ l_url('news/daily', [$prev->post_date]) }}">< {{ $prev->post_date }}</a>
                </div>
            @endif
            @if($next)
                <div class="col-md-2 col-md-offset-8">
                    <a class="label label-info" href="{{ l_url('news/daily', [$next->post_date]) }} ">{{ $next->post_date }} ></a>
                </div>
            @endif
        </div>

        <br>
    </div>
</section>
@endsection
