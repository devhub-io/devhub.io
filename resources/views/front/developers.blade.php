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
        <h1 style="text-align: center">Developers</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="row">
                            @foreach($developers as $item)
                                <div class="col-md-3">
                                    <div class="thumbnail" style="height: 300px;">
                                        <a href="{{ l_url('developer', [$item->login]) }}"><img src="{{ $item->avatar_url ? $item->avatar_url : cdn_asset('img/200x200.png') }}" alt="{{ $item->login }}" title="{{ $item->login }}" class="lazyload" width="200"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('developer', [$item->login]) }}"><h4>@if($item->type == 'Organization') <i class="fa fa-users" title="Organization"></i> @else <i class="fa fa-user" title="User"></i> @endif {{ $item->name ? $item->name : $item->login }}</h4></a>
                                            <div style="margin-bottom: 10px">
                                                <span title="star">
                                                    <a aria-label="Follow @{{ $developer->login }} on GitHub" data-count-aria-label="# followers on GitHub" data-count-api="/users/{{ $item->login }}#followers" data-count-href="/{{ $item->login }}/followers" href="https://github.com/{{ $item->login }}" class="github-button">Follow</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{ $developers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script async defer src="https://buttons.github.io/buttons.js"></script>
@endsection
