@extends('layouts.front')

@section('styles')
    <style>
        .single-sidebar li {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0 10px 20px;
        }
        .single-sidebar li.active {
            color: #fff;
            background-color: #1abc9c;
        }
        .single-sidebar li.active a {
            color: #fff;
        }
    </style>
@endsection

@section('contents')
<section id="content">
    <div class="single-product-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(isset($title)) <h1 style="text-align: center">{{ $title }}</h1> @endif
                    @if(isset($explain)) <p style="margin-bottom: 25px;">{{ $explain }}</p> @endif
                </div>
            </div>
            <div class="row">
                @if(isset($child_category))
                <div class="col-md-3">
                    <div class="single-sidebar">
                        <h2 class="sidebar-title" style="font-size: 28px">@lang('front.child_category')</h2>
                        <ul>
                            @foreach($child_category as $item)
                            <li class="{{ active_class($item->slug == $select_slug) }}"><a href="{{ l_url('category', [$item->slug]) }}" style="font-size: 19px;">@lang('category.'.$item->slug)</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <div class="@if(isset($child_category)) col-md-9 @else col-md-12 @endif">
                    <div class="product-content-right">
                        <div class="row">
                            @foreach($repos as $item)
                            <div class="col-sm-6 @if(isset($child_category)) col-md-4 @else col-md-3 @endif">
                                <div class="thumbnail" style="height: 390px;">
                                    <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->cover ? $item->cover . '&s=200' : cdn_asset('img/200x200.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="200"></a>
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h4>{{ $item->title }}</h4></a>
                                        <div style="margin-bottom: 10px">
                                            <span title="star">
                                                <i class="glyphicon glyphicon-star"></i> {{ $item->stargazers_count }}
                                            </span>
                                            <span class="line">{{ $item->trends }}</span>
                                        </div>
                                        <p>{{ mb_substr($item->description, 0, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{ $repos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
