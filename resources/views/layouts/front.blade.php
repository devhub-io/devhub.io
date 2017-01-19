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
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png" />
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
                    <h1><a href="/"><span>Dev</span>Hub</a></h1>
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
                    <li class="{{ active_class(if_uri('/')) }}"><a href="{{ l_url('/') }}">@lang('front.home')</a></li>
                    @if(isset($one_column))
                        @foreach($one_column as $item)
                            <li class="{{ active_class($item->slug == $current_category_slug) }}"><a href="{{ l_url('category', [$item->slug]) }}">@lang('category.'.$item->slug)</a></li>
                        @endforeach
                    @endif
                    <li class="{{ active_class(if_uri('developers') || if_route('developer')) }}"><a href="{{ l_url('developers') }}">Developers</a></li>
                    <li class="{{ active_class(if_uri('news') || if_route('news')) }}"><a href="{{ l_url('news') }}">News</a></li>
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
                    <h2><span>DevHub</span></h2>
                    <p>@lang('front.about_develophub')</p>
                    <p>
                        Currently tracking
                        <a href="{{ l_url('list/newest') }}" style="color: white">{{ isset($repos_total) ? number_format($repos_total) : 0 }}</a> open source projects,
                        <a href="{{ l_url('developers') }}" style="color: white">{{ isset($developers_total) ? number_format($developers_total) : 0 }}</a> developers
                    </p>
                    <div class="footer-social">
                        <a target="_blank" href="https://www.facebook.com/devhubdotio"><i class="fa fa-facebook"></i></a>
                        <a target="_blank" href="https://twitter.com/HubDevelop"><i class="fa fa-twitter"></i></a>
                        <a href="mailto:devhub.io@gmail.com"><i class="fa fa-envelope"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <h2 class="footer-wid-title">@lang('front.website') </h2>
                    <ul>
                        <li><a href="#">@lang('front.about')</a></li>
                        <li><a href="#">@lang('front.contact_us')</a></li>
                        <li><a href="//status.devhub.io/">@lang('front.status')</a></li>
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
                        <li><a href="{{ l_url('developers') }}">Developers</a></li>
                        <li><a href="{{ l_url('news') }}">News</a></li>
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
                    <p>&copy; 2016 - 2017 DevHub.io. All Rights Reserved.</p>
                    <p style="font-size: 10px; color: #bdbdbd">Disclaimer: This project is not affiliated with the GitHub company in any way.
                        <br> GitHub® and the Octocat® logo are registered trademarks of GitHub, Inc., used with permission—https://github.com/logos</p>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/peity/3.2.0/jquery.peity.min.js"></script>
@include('layouts.javascript_bind')
@yield('scripts')
<script>
    console.info(
'     _____             _    _       _      \n' +
'    |  __ \\           | |  | |     | |    \n' +
'    | |  | | _____   _| |__| |_   _| |__  \n' +
'    | |  | |/ _ \\ \\ / /  __  | | | | \'_ \\ \n' +
'    | |__| |  __/\\ V /| |  | | |_| | |_) |   \n' +
'    |_____/ \\___| \\_/ |_|  |_|\\__,_|_.__/ \n' +
'    --------------------------------------\n' +
'                         https://devhub.io      ');

    $("span.line").peity("line");
</script>
<script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', 'UA-35989028-4', 'auto');
    ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<script>
    if (typeof ga !== "undefined" && ga !== null) {
        $(document).ajaxSend(function (event, xhr, settings) {
            ga('send', 'pageview', settings.url);
        });

        (function (window) {
            var undefined,
                link = function (href) {
                    var a = window.document.createElement('a');
                    a.href = href;
                    return a;
                };
            window.onerror = function (message, file, line, column) {
                var host = link(file).hostname;
                ga('send', {
                    'hitType': 'event',
                    'eventCategory': (host == window.location.hostname || host == undefined || host == '' ? '' : 'external ') + 'error',
                    'eventAction': message,
                    'eventLabel': (file + ' LINE: ' + line + (column ? ' COLUMN: ' + column : '')).trim(),
                    'nonInteraction': 1
                });
            };
        }(window));

        $(function () {
            var isDuplicateScrollEvent,
                scrollTimeStart = new Date,
                $window = $(window),
                $document = $(document),
                scrollPercent;

            $window.scroll(function () {
                scrollPercent = Math.round(100 * ($window.height() + $window.scrollTop()) / $document.height());
                if (scrollPercent > 90 && !isDuplicateScrollEvent) { //page scrolled to 90%
                    isDuplicateScrollEvent = 1;
                    ga('send', 'event', 'scroll',
                        'Window: ' + $window.height() + 'px; Document: ' + $document.height() + 'px; Time: ' + Math.round((new Date - scrollTimeStart ) / 1000, 1) + 's'
                    );
                }
            });
        });

        if (window.performance) {
            var timeSincePageLoad = Math.round(performance.now());
            ga('send', 'timing', 'JS Dependencies', 'load', timeSincePageLoad);
        }
    }
</script>
<script>
    (function(h,e,a,t,m,p) {
        m=e.createElement(a);m.async=!0;m.src=t;
        p=e.getElementsByTagName(a)[0];p.parentNode.insertBefore(m,p);
    })(window,document,'script','https://u.heatmap.it/log.js');
</script>
<script type="text/javascript">
    window.doorbellOptions = {
        appKey: 'akmfqdy1fBgL1corAEydarDdZdwk4P55B94bk8vMbIvnXUTD7mxxYsXKrHeY96fG'
    };
    (function(w, d, t) {
        var hasLoaded = false;
        function l() { if (hasLoaded) { return; } hasLoaded = true; window.doorbellOptions.windowLoaded = true; var g = d.createElement(t);g.id = 'doorbellScript';g.type = 'text/javascript';g.async = true;g.src = 'https://embed.doorbell.io/button/5155?t='+(new Date().getTime());(d.getElementsByTagName('head')[0]||d.getElementsByTagName('body')[0]).appendChild(g); }
        if (w.attachEvent) { w.attachEvent('onload', l); } else if (w.addEventListener) { w.addEventListener('load', l, false); } else { l(); }
        if (d.readyState == 'complete') { l(); }
    }(window, document, 'script'));
</script>
</body>
</html>
