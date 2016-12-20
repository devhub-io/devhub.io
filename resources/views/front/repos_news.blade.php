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
        {!! Breadcrumbs::render('repos_news', $repos) !!}

        <div class="row">
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>{{ $repos->title }}</h1>
                    <p>{{ $repos->description }}</p>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                @if($news->count() > 0)
                    <h2>Related News</h2>
                    <div class="row">
                        @foreach($news as $item)
                            <div class="col-md-12">
                                <div style="height: 90px;">
                                    <div class="caption">
                                        <a href="{{ link_url('https://news.ycombinator.com/item?id='.$item->item_id) }}" rel="nofollow" target="_blank"><h3>{{ $item->title }}</h3></a>
                                        <div style="margin-bottom: 10px">
                                            <span title="Score">
                                                <i class="glyphicon glyphicon-info-sign"></i> {{ $item->score }} &nbsp;&nbsp;
                                            </span>
                                            <span title="Post date">
                                                <i class="glyphicon glyphicon-time"></i> {{ $item->post_date }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <br>
    </div>
@endsection
