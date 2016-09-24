<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocaleRegional() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    <meta name="theme-color" content="#1abc9c">
    <link rel="alternate" href="{{ url('/') }}" hreflang="x-default"/>
    <link rel="alternate" href="{{ url('en') }}" hreflang="en" />
    <link rel="alternate" href="{{ url('zh') }}" hreflang="zh-Hans" />
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('sitemap') }}">
    <link rel="alternate" type="application/rss+xml" title="RSS" href="{{ url('feed') }}">
    <link rel="search" type="application/opensearchdescription+xml" href="{{ url('opensearch.xml') }}" title="DevelopHub">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ cdn_asset(elixir('css/all.css')) }}">
    @yield('styles')
    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="user-menu">
                    <ul>
                        <li><a href="javascript:void(0);"><i class="fa fa-user"></i> @lang('front.my_account')</a></li>
                        <li><a href="{{ l_url('sites') }}"><i class="fa fa-sitemap"></i> @lang('front.sites')</a></li>
                        {{--<li><a href="{{ l_url('submit') }}"><i class="fa fa-plus"></i> @lang('front.submit_repository')</a></li>--}}
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="header-right">
                    <ul class="list-unstyled list-inline">
                        <li class="dropdown dropdown-small">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="#">
                                <span class="key">@lang('front.choose_language') : </span><span class="value">{{ Localization::getCurrentLocaleNative() }} </span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach(Localization::getSupportedLocales() as $localeCode => $properties)
                                    <li>
                                        <a rel="alternate" hreflang="{{$localeCode}}" href="/{{ $localeCode }}">
                                            {{ $properties['native'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End header area -->

<div class="site-branding-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="logo">
                    <h1><a href="/"><span>Develop</span>Hub</a></h1>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="shopping-item">
                    <form action="{{ l_url('search') }}">
                        <input type="search" name="keyword" value="" placeholder="@lang('front.search_placeholder')">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End site branding area -->

<div class="mainmenu-area">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ active_class(if_uri('/')) }}"><a href="/">@lang('front.home')</a></li>
                    @if(isset($one_column))
                        @foreach($one_column as $item)
                            <li class="{{ active_class($item->slug == $current_category_slug) }}"><a href="{{ l_url('category', [$item->slug]) }}">@lang('category.'.$item->slug)</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div> <!-- End mainmenu area -->

@yield('contents')

<div class="footer-top-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="footer-about-us">
                    <h2><span>DevelopHub</span></h2>
                    <p>@lang('front.about_develophub')</p>
                    <div class="footer-social">
                        <a target="_blank" href="#"><i class="fa fa-facebook"></i></a>
                        <a target="_blank" href="https://twitter.com/HubDevelop"><i class="fa fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <h2 class="footer-wid-title">@lang('front.website') </h2>
                    <ul>
                        <li><a href="#">@lang('front.about')</a></li>
                        <li><a href="#">@lang('front.contact_us')</a></li>
                        <li><a href="//status.develophub.net/">@lang('front.status')</a></li>
                        <li><a href="#">@lang('front.api')</a></li>
                        <li><a href="{{ l_url('feed') }}" target="_blank" title="RSS Link">Feed</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <h2 class="footer-wid-title">@lang('front.category')</h2>
                    <ul>
                        @if(isset($one_column))
                            @foreach($one_column as $item)
                            <li><a href="{{ l_url('category', [$item->slug]) }}">@lang('category.'.$item->slug)</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-newsletter">
                    <h2 class="footer-wid-title">@lang('front.weekly_title')</h2>
                    <p>@lang('front.weekly_subtitle')</p>
                    <div class="newsletter-form">
                        <form action="#">
                            <input type="email" placeholder="@lang('front.enter_email')">
                            <input type="submit" value="@lang('front.subscribe')">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End footer top area -->

<div class="footer-bottom-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="copyright">
                    <p>&copy; 2016 DevelopHub. All Rights Reserved.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-card-icon">
                    @if(isset($badger))
                        @foreach($badger as $item)
                            {!! $item !!}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> <!-- End footer bottom area -->

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="inputEmail1">Email address</label>
                        <input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_remember" value="1"> Remember Password
                        </label>
                    </div>
                    <button type="submit" class="btn btn-default">Login</button>
                </form>
                <hr>
                <h5>Another Account login</h5>
                <a href="{{ l_url('socialite/github/redirect') }}" style="margin-right: 30px;"><i class="fa fa-github fa-3x"></i></a>
                <a href="{{ l_url('socialite/bitbucket/redirect') }}" style="margin-right: 30px;"><i class="fa fa-bitbucket fa-3x"></i></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="{{ cdn_asset(elixir('js/app.js')) }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.18.0/algoliasearch.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.21.3/autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/peity/3.2.0/jquery.peity.min.js"></script>
@yield('scripts')
<script>
    var client = algoliasearch('{{ env('ALGOLIA_APP_ID') }}', '{{ env('ALGOLIA_SEARCH_KEY') }}');
    var index = client.initIndex('{{ env('SCOUT_PREFIX') }}repos');
    autocomplete('input[name=keyword]', {hint: false}, [
        {
            source: autocomplete.sources.hits(index, {hitsPerPage: 10, filters: "status=1"}),
            displayKey: 'title',
            templates: {
                suggestion: function (suggestion) {
                    return suggestion._highlightResult.title.value;
                }
            }
        }
    ]).on('autocomplete:selected', function (event, suggestion, dataset) {
        console.log(suggestion, dataset);
    });

    $("span.line").peity("line");
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-35989028-3', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
