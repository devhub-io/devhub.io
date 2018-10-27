<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    <!-- Bootstrap -->
    <link href="/components/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/components/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/components/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/components/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/components/gentelella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="/components/gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="/components/gentelella/build/css/custom.min.css" rel="stylesheet">
    <style>
        .app-pagination li {
            float: left;
            width: 50px;
        }

        .app-pagination a {
            font-size: 15px;
            display: inline-block;
            width: 50px;
            height: 50px;
            line-height: 50px;
        }

        .app-pagination li {
            list-style: none;
            width: 50px;
            height: 50px;
            line-height: 50px;
        }
    </style>
    @yield('styles')
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="/admin" class="site_title"><span>DevHub.io</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="/components/gentelella/production/images/user.png" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>{{ Auth::user()->name }}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>&nbsp;</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-file"></i> Repos <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('admin/categories') }}">分类</a></li>
                                    <li><a href="{{ url('admin/repos') }}">资源</a></li>
                                    <li><a href="{{ url('admin/url') }}">链接</a></li>
                                    <li><a href="{{ url('admin/developer') }}">开发者</a></li>
                                    <li><a href="{{ url('admin/developer_url') }}">开发者链接</a></li>
                                    <li><a href="{{ url('admin/sites') }}">站点</a></li>
                                    <li><a href="{{ url('admin/collections') }}">集合</a></li>
                                    <li><a href="{{ url('admin/vote') }}">投票</a></li>
                                    <li><a href="{{ url('admin/click') }}">点击</a></li>
                                    <li><a href="{{ url('admin/topics') }}">主题</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-archive"></i> Article <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('admin/articles') }}">内容</a></li>
                                    <li><a href="{{ url('admin/articles/url') }}">链接</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-envelope"></i> Mail <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('admin/mail/template') }}">Template</a></li>
                                    <li><a href="{{ url('admin/mail/subscriber') }}">Subscriber</a></li>
                                    <li><a href="{{ url('admin/mail/publish') }}">Publish</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-sitemap"></i> System <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('admin/user') }}">User</a></li>
                                    <li><a href="{{ url('log-viewer') }}">Log</a></li>
                                    <li><a href="{{ url('admin/api/status') }}">API</a></li>
                                    <li><a href="{{ url('admin/queue/status') }}">Queue</a></li>
                                    <li><a href="{{ url('admin/decompose') }}" target="_blank">Decompose</a></li>
                                    <li><a href="{{ url('admin/maintenance') }}">Maintenance</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Now: {{ date('Y-m-d H:i:s') }}">
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('logout') }}">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="/components/gentelella/production/images/user.png" alt="">{{ Auth::user()->name }}
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="{{ url('admin/user/profile') }}"> Profile</a></li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>

                        @if(isset($github_status))
                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                    @if($github_status['status'] == 'good')
                                        <span class="bg-green"><i class="fa fa-github"></i></span>
                                    @elseif($github_status['status'] == 'minor')
                                        <span class="bg-orange"><i class="fa fa-github"></i></span>
                                    @else
                                        <span class="bg-red"><i class="fa fa-github"></i></span>
                                    @endif
                                </a>
                                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                    @foreach($github_status_messages as $item)
                                    <li>
                                        <a>
                                            <span>
                                              <span>{{ $item['status'] }}</span>
                                              <span class="time">{{ $item['created_on'] }}</span>
                                            </span>
                                            <span class="message">
                                              {{ $item['body'] }}
                                            </span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        <li>
                            <a href="{{ url('/') }}" target="_blank">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" id="app">
            <div style="margin: 65px 10px 10px 10px;">
                @include('flash::message')
            </div>
            @yield('contents')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                <p>&copy; 2016-2017 DevHub.io. All Rights Reserved.</p>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="/components/gentelella/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/components/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/components/gentelella/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="/components/gentelella/vendors/nprogress/nprogress.js"></script>
<!-- bootstrap-progressbar -->
<script src="/components/gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="/components/gentelella/vendors/iCheck/icheck.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="/components/gentelella/build/js/custom.min.js"></script>

@yield('scripts')
</body>
</html>
