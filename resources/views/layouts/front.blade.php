<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocaleRegional() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    <link rel="alternate" href="{{ url('/') }}" hreflang="x-default"/>
    <link rel="alternate" href="{{ url('en') }}" hreflang="en" />
    <link rel="alternate" href="{{ url('zh') }}" hreflang="zh-Hans" />
    <link rel="search" type="application/opensearchdescription+xml" href="{{ url('opensearch.xml') }}" title="DevelopHub">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ cdn_asset(elixir('css/all.css')) }}">
    @yield('styles')
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="user-menu">
                    <ul>
                        <li><a href="#"><i class="fa fa-user"></i> @lang('front.my_account')</a></li>
                        <li><a href="{{ l_url('sites') }}"><i class="fa fa-sitemap"></i> @lang('front.sites')</a></li>
                        <li><a href="{{ l_url('submit') }}"><i class="fa fa-plus"></i> @lang('front.submit_repository')</a></li>
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
                        <input type="search" name="keyword" value="" placeholder="@lang('front.search_repository')">
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
                    {{--<div class="footer-social">--}}
                        {{--<span class='st_sharethis_large' displayText='ShareThis'></span>--}}
                        {{--<span class='st_facebook_large' displayText='Facebook'></span>--}}
                        {{--<span class='st_twitter_large' displayText='Tweet'></span>--}}
                        {{--<span class='st_linkedin_large' displayText='LinkedIn'></span>--}}
                        {{--<span class='st_pinterest_large' displayText='Pinterest'></span>--}}
                        {{--<span class='st_email_large' displayText='Email'></span>--}}
                    {{--</div>--}}
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
        </div>
    </div>
</div> <!-- End footer bottom area -->

<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="{{ cdn_asset('components/holderjs/holder.min.js') }}"></script>
<script src="{{ cdn_asset(elixir('js/app.js')) }}"></script>
@yield('scripts')
{{--<script type="text/javascript">var switchTo5x=true;</script>--}}
{{--<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>--}}
{{--<script type="text/javascript">stLight.options({publisher: "f6598daa-4ed2-462c-9c4c-ec742a440449", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>--}}
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
