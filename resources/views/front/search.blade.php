@extends('layouts.front')

@section('contents')
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-left">
                        <div class="row">
                            @foreach($repos as $item)
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail" style="height: 362px;">
                                        <a href="{{ l_url('repos', [$item->slug]) }}">
                                            <img src="{{ $item->image > 0 ? image_url($item->image, ['w' => 300]) : 'holder.js/300x300' }}" alt="{{ $item->title }}" title="{{ $item->title }}"></a>
                                        <div class="caption">
                                            <a href="{{ l_url('repos', [$item->slug]) }}"><h3>{{ $item->title }}</h3></a>
                                            <p>{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{ $repos->appends(['keyword' => $keyword])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
