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
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
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

                <div class="col-md-9">
                    <div class="product-content-right">
                        <div class="row">
                            @foreach($repos as $item)
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail" style="height: 362px;">
                                    <a href="{{ l_url('repos', [$item->slug]) }}"><img src="{{ $item->image > 0 ? image_url($item->image, ['h' => 200]) : cdn_asset('img/200x200.png') }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="lazyload"></a>
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->title }}</h3></a>
                                        <p>{{ $item->description }}</p>
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
