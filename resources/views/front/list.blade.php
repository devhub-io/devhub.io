@extends('layouts.front')

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
                            <li><a href="{{ l_url('category', [$item->slug]) }}">{{ $item->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="product-content-right">
                        <div class="row">
                            @foreach($repos as $item)
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail"><a href="{{ l_url('repos', [$item->slug]) }}"><img alt="100%x200" data-src="holder.js/100%x200"
                                                            style="height: 200px; width: 100%; display: block;"
                                                            src="{{ image_url($item->image, ['w' => 242]) }}"
                                                            data-holder-rendered="true"></a>
                                    <div class="caption">
                                        <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->title }}</h3></a>
                                        <p>{{ $item->description }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
