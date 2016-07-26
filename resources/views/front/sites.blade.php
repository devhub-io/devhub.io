@extends('layouts.front')

@section('styles')
    <style>
        .favicon {
            width: 20px;
            height: 20px;
        }
        .site {
            margin-bottom: 12px;
        }
        .site-index {
            font-size: 15px;
        }
    </style>

@endsection

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container site-index">

            @foreach($sites as $category => $group)
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <i class="fa fa-globe text-md"></i> @lang("category.{$category}")
                    </div>

                    <div class="panel-body row">
                        @foreach($group as $item)
                            <div class="col-md-2 site">
                                <a data-content="{{ $item->description }}" href="{{ $item->url }}" target="_blank"  title="{{ $item->title }}"><img src="{{ asset($item->icon) }}" class="favicon">{{ $item->title }}</a>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
