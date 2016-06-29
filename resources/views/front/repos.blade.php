@extends('layouts.front')

@section('contents')
    <div class="container">
        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-4 col-sm-4 hidden-xs">
                <img class="cover" alt="100%x200" data-src="holder.js/100%x200"
                     style="height: 200px; width: 100%; display: block;"
                     src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU1OTc3ZWIxOTAgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTU5NzdlYjE5MCI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OC44NDk5OTg0NzQxMjExIiB5PSIxMDUuNyI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg=="
                     data-holder-rendered="true">
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="repo-title">
                    <h1>
                        {{ $repos->title }}
                    </h1>
                    <p>
                        {{ $repos->description }}
                    </p>
                </div>
                <div class="menu hidden-xs">
                    @if($repos->homepage)
                    <a target="_blank" href="{{ $repos->homepage }}"><i class="fa fa-home"></i> 官 网 </a>
                    @endif
                    @if($repos->github)
                    <a target="_blank" href="{{ $repos->github }}" class="gitbtn"><i class="fa fa-github"></i> Github </a>
                    @endif
                </div>
                <div class="params hidden-xs">
                    <div style="border-left: 0" title="星标数量">
                        <i class="fa fa-star"></i> <span>{{ $repos->stargazers_count }}</span>
                    </div>
                    <div title="最后更新时间">
                        <i class="fa fa-clock-o"></i> <span>{{ $repos->repos_updated_at }}</span>
                    </div>
                    <div title="Fork数量">
                        <i class="fa fa-code-fork"></i> <span>{{ $repos->forks_count }}</span>
                    </div>
                    {{--<div title="issue 响应速度">--}}
                        {{--<i class="fa fa-exclamation-circle"></i> <span>{{ $repos-> }}</span>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Non-responsive Bootstrap</h1>
                    <p class="lead">Disable the responsiveness of Bootstrap by fixing the width of the container and using the first grid system tier. <a href="http://getbootstrap.com/getting-started/#disable-responsive">Read the documentation</a> for more information.</p>
                </div>

                <h3>What changes</h3>
                <p>Note the lack of the <code>&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;</code>, which disables the zooming aspect of sites in mobile devices. In addition, we reset our container's width and changed the navbar to prevent collapsing, and are basically good to go.</p>

                <h3>Regarding navbars</h3>
                <p>As a heads up, the navbar component is rather tricky here in that the styles for displaying it are rather specific and detailed. Overrides to ensure desktop styles display are not as performant or sleek as one would like. Just be aware there may be potential gotchas as you build on top of this example when using the navbar.</p>

                <h3>Browsers, scrolling, and fixed elements</h3>
                <p>Non-responsive layouts highlight a key drawback to fixed elements. <strong class="text-danger">Any fixed component, such as a fixed navbar, will not be scrollable when the viewport becomes narrower than the page content.</strong> In other words, given the non-responsive container width of 970px and a viewport of 800px, you'll potentially hide 170px of content.</p>
                <p>There is no way around this as it's default browser behavior. The only solution is a responsive layout or using a non-fixed element.</p>

                <h3>Non-responsive grid system</h3>
            </div>
        </div>-->
    </div>

@endsection