@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="text-align: center;min-height: 300px;">
                    <h1>OOPS! - Could not Find it</h1>
                    <a href="{{ url('/') }}" class="btn btn-info">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
