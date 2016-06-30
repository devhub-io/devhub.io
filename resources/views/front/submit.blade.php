@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="single-sidebar">
                        @if($status == 'ok')
                            <h1>@lang('front.thank_submit')</h1>
                            <h4>@lang('front.review_submit')<a href="{{ l_url('/') }}">@lang('front.view_repository')</a> @lang('front.or') <a href="{{ l_url('submit') }}">@lang('front.continue_submit')</a></h4>
                        @elseif($status == 'exists')
                            <h1>@lang('front.exists_submit')<a href="{{ $url }}">@lang('front.visit')</a></h1>
                        @else
                            <h1>@lang('front.submit_title')</h1>
                            <h2 class="sidebar-title">@lang('front.submit_subtitle')</h2>
                            <form action="{{ l_url('submit') }}" method="post">
                                {!! csrf_field() !!}
                                <input type="url" placeholder="https://github.com/twbs/bootstrap" name="url" class="input-medium" style="width: 500px;">
                                <input type="submit" value="@lang('front.submit_button')">
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection