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
                                                            src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU1OTc3ZWIxOTAgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTU5NzdlYjE5MCI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OC44NDk5OTg0NzQxMjExIiB5PSIxMDUuNyI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg=="
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