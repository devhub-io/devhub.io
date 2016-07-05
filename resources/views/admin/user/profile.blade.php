@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Profile</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <img src="{{ $url }}" alt="">
        </div>
    </div>
@endsection
