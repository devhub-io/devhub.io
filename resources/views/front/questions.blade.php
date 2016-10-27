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
        {!! Breadcrumbs::render('questions', $repos) !!}

        <div class="row">
            <div class="col-md-7 col-sm-8">
                <div class="repo-title">
                    <h1>{{ $repos->title }} <span class="line" title="Trends">{{ $repos->trends }}</span></h1>
                    <p>{{ $repos->description }}</p>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                @if($repos->questions->count() > 0)
                    <h2>Related Questions</h2>
                    <div class="row">
                        @foreach($repos->questions as $item)
                            <div class="col-md-12">
                                <div style="height: 90px;">
                                    <div class="caption">
                                        <a href="{{ link_url($item->link) }}" rel="nofollow" target="_blank"><h3>{{ $item->title }}</h3></a>
                                        <div style="margin-bottom: 10px">
                                            <span title="View count">
                                                <i class="glyphicon glyphicon-eye-open"></i> {{ $item->view_count }} &nbsp;&nbsp;
                                            </span>
                                            <span title="Score">
                                                <i class="glyphicon glyphicon-info-sign"></i> {{ $item->score }} &nbsp;&nbsp;
                                            </span>
                                            <span title="Last activity date">
                                                <i class="glyphicon glyphicon-time"></i> {{ Carbon\Carbon::now()->diffForHumans(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->last_activity_date)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ link_url(stackoverflow_tagged_url($repos->repo)) }}" rel="nofollow">More Questions ......</a>
            </div>
        </div>

        <br>
    </div>
@endsection
