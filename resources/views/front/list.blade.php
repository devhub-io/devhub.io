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
    <div class="single-product-area">
        @if(isset($title)) <h1 style="text-align: center">{{ $title }}</h1> @endif
        <div class="container">
            <div class="row">
                @if(isset($child_category))
                <div class="col-md-3">
                    <div class="single-sidebar">
                        <h2 class="sidebar-title">@lang('front.child_category')</h2>
                        <ul>
                            @foreach($child_category as $item)
                            <li class="{{ active_class($item->slug == $slug) }}"><a href="{{ l_url('category', [$item->slug]) }}">@lang('category.'.$item->slug)</a></li>
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
                                <div class="thumbnail" style="height: 362px;">
                                    <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->image > 0 ? image_url($item->image, ['h' => 200]) : ($item->cover ? $item->cover : cdn_asset('img/200x200.png')) }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload" width="200"></a>
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->title }}</h3></a>
                                        <span class="line">{{ $item->trends }}</span>
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
@endsection
