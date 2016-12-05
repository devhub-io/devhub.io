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
        {!! Breadcrumbs::render('news') !!}

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="repo-title">
                    <h1>What's hot on Hacker News on {{ date('Y-m-d') }}</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if($news->count() > 0)
                    <div class="row">
                        @foreach($news as $item)
                            <div class="col-md-12">
                                <div style="height: 90px;">
                                    <div class="caption">
                                        <a href="{{ link_url('https://news.ycombinator.com/item?id='.$item->item_id) }}" rel="nofollow" target="_blank"><h3>{{ $item->title }}</h3></a>
                                        <div style="margin-bottom: 10px">
                                            <span title="View count">
                                                <i class="glyphicon glyphicon-eye-open"></i> {{ $item->repos->view_count }} &nbsp;&nbsp;
                                            </span>
                                            <span title="Score">
                                                <i class="glyphicon glyphicon-info-sign"></i> {{ $item->score }} &nbsp;&nbsp;
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
