@extends('layouts.front')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($topics->count() > 0)
                    <h2 style="margin-top: 50px;margin-bottom: 50px;">Topics</h2>
                    <div class="row">
                        @foreach($topics as $item)
                            <div class="col-md-2">
                                <div class="thumbnail" style="height: 50px;">
                                    <div class="caption">
                                        <a href="{{ l_url('topic', [$item->topic]) }}"><h4>{{ $item->topic }} ({{ $item->number }})</h4></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
