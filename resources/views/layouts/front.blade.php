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
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700,500' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ cdn_asset(elixir('css/all.css')) }}">
    @yield('styles')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>.async-hide { opacity: 0 !important} </style>
    <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
            h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
            (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
        })(window,document.documentElement,'async-hide','dataLayer',4000,
            {'GTM-T37PMGT':true});</script>
</head>
<body>

<header id="main-nav">
    <div class="container">

        <a id="navigation" href="#"><i class="fa fa-bars"></i></a>

        <div id="slide_out_menu">
            <a href="#" class="menu-close"><i class="fa fa-times"></i></a>
            <div class="logo" style="color: #ffffff">DevHub.io</div>
            <ul>
                <li><a href="{{ l_url('/developers') }}">Developers</a></li>
                <li><a href="{{ l_url('/topics') }}">Topics</a></li>
                <li><a href="{{ l_url('/news') }}">News</a></li>
                <li><a href="javascript:alert('Coming soon.')">My account</a></li>
                <li><a href="{{ l_url('/sites') }}">Sites</a></li>
                <li><a href="{{ l_url('search') }}" class="btn btn-blue">Search...</a></li>
            </ul>

            <div class="slide_out_menu_footer">
                <div class="more-info">
                    <p>Made with love by <a href="http://getcraftwork.com">Craft Work</a></p>
                    <p>Developed by <a href="http://ruibogasdesign.net/">Rui Bogas</a>
                </div>
                <ul class="socials">
                    <li><a href="https://twitter.com/HubDevelop" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.facebook.com/devhubdotio" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="mailto:devhub.io@gmail.com"><i class="fa fa-envelope-o"></i></a></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <ul class="left">
                    <li><a href="{{ l_url('/developers') }}">Developers</a></li>
                    <li><a href="{{ l_url('/topics') }}">Topics</a></li>
                    <li><a href="{{ l_url('/news') }}">News</a></li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <a href="{{ l_url('/') }}" class="logo">DevHub.io</a>
            </div>
            <div class="col-md-4">
                <ul class="right">
                    <li><a href="javascript:alert('Coming soon.')" class="help">My account</a></li>
                    <li><a href="{{ l_url('/sites') }}">Sites</a></li>
                    <li><a href="{{ l_url('/search') }}" class="btn btn-blue">Search...</a></li>
                </ul>
            </div>
        </div>
    </div>
</header><!-- //Main Nav -->

@yield('contents')

<footer id="footer">
    <div class="container footer-container">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ l_url('/') }}" style="color: #26272d; font-weight: 700; text-transform: uppercase;font-size: 12px;">DevHub.io</a>
                <p>Recommended high-quality free and open source development tools, resources, reading. <br>
                    Currently tracking
                    <a href="{{ l_url('list/newest') }}">{{ isset($repos_total) ? number_format($repos_total) : 0 }}</a> open source projects,
                    <a href="{{ l_url('developers') }}">{{ isset($developers_total) ? number_format($developers_total) : 0 }}</a> developers</p>
                <ul class="socials">
                    <li><a href="https://twitter.com/HubDevelop" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.facebook.com/devhubdotio" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="mailto:devhub.io@gmail.com"><i class="fa fa-envelope-o"></i></a></li>
                </ul>
            </div>
            <div class="col-md-2 col-md-offset-4 col-sm-4 col-xs-6 footer-links">
                <ul>
                    <li><p class="title">@lang('front.website')</p></li>
                    <li><a href="#">@lang('front.about')</a></li>
                    <li><a href="#">@lang('front.contact_us')</a></li>
                    <li><a href="//status.devhub.io/">@lang('front.status')</a></li>
                    <li><a href="#">@lang('front.api')</a></li>
                    <li><a href="{{ l_url('feed') }}">Feed</a></li>
                </ul>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 footer-links">
                <ul>
                    <li><p class="title">GATEGORY</p></li>
                    @if(isset($one_column))
                        @foreach($one_column as $item)
                            <li><a href="{{ l_url('category', [$item->slug]) }}">@lang('category.'.$item->slug)</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="container copyright-container">
        <div class="row">
            <div class="col-md-8 text-left">
                <div class="more-info">
                    <p class="copyright-title">© 2016 - 2017 DevHub.io. All Rights Reserved.</p>
                    <p class="copyright-tips">Disclaimer: This project is not affiliated with the GitHub company in any way.
                        GitHub® and the Octocat® logo are registered trademarks of GitHub, Inc., used with permission—https://github.com/logos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 text-right">
                        <div class="made-by">Power by</div>
                    </div>
                    <div class="col-md-6">
                        @if(isset($badger))
                            @foreach($badger as $item)
                                {!! $item !!}
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
    ga('require', 'GTM-T37PMGT');
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
