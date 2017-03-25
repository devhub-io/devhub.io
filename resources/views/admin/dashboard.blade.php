@extends('layouts.admin')

@section('contents')
    <!-- top tiles -->
    <div class="row tile_count">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-sitemap"></i> Total Repositories</span>
            <div class="count">{{ number_format($repos_count) }}</div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Total Developers</span>
            <div class="count">{{ number_format($developers_count) }}</div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Unique Visitors (Last month | CloudFlare)</span>
            <div class="count">{{ number_format($cf['totals']['uniques']['all']) }}</div>
        </div>
    </div>
    <!-- /top tiles -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        <a href="https://www.cloudflare.com/a/analytics/devhub.io/unique_visitors" target="_blank"><i class="fa fa-link"></i></a>
                        CloudFlare <small>Last 31 days</small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="dashboard-widget-content">
                        <div class="col-md-4 hidden-small">
                            <h2 class="line_30">http_status ({{ number_format($cf['totals']['requests']['all']) }})</h2>

                            <table class="countries_list">
                                <tbody>
                                @foreach($http_status as $code => $item)
                                    <tr>
                                        <td>{{ $code }}</td>
                                        <td class="fs15 fw700 text-right">{{ number_format($item) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 hidden-small">
                            <h2 class="line_30">pageviews ({{ number_format($cf['totals']['pageviews']['all']) }})</h2>

                            <table class="countries_list">
                                <tbody>
                                @foreach($pageviews as $engine => $item)
                                    <tr>
                                        <td>{{ $engine }}</td>
                                        <td class="fs15 fw700 text-right">{{ number_format($item) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        <a href="https://analytics.google.com/analytics/web/#report/defaultid/a35989028w128044318p131763022/%3F_u.dateOption%3Dlast30days/" target="_blank"><i class="fa fa-link"></i></a>
                        Google Analytics <small>Last 31 days</small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="dashboard-widget-content">
                        <div class="col-md-4 hidden-small">
                            <h2 class="line_30">Top Browsers</h2>

                            <table class="countries_list">
                                <tbody>
                                @foreach($topBrowsers as $item)
                                    <tr>
                                        <td>{{ $item['browser'] }}</td>
                                        <td class="fs15 fw700 text-right">{{ number_format($item['sessions']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 hidden-small">
                            <h2 class="line_30">Top Referrers</h2>

                            <table class="countries_list">
                                <tbody>
                                @foreach($topReferrers as $item)
                                    <tr>
                                        <td><a href="http://{{ $item['url'] }}" target="_blank">{{ $item['url'] }}</a></td>
                                        <td class="fs15 fw700 text-right">{{ number_format($item['pageViews']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 hidden-small">
                            <h2 class="line_30">Most Visited Pages</h2>

                            <table class="countries_list">
                                <tbody>
                                @foreach($mostVisitedPages as $item)
                                    <tr>
                                        <td><a href="{{ $item['url'] }}" target="_blank">{{ $item['pageTitle'] }}</a></td>
                                        <td class="fs15 fw700 text-right">{{ number_format($item['pageViews']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
