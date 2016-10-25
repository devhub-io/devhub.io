@extends('layouts.admin')

@section('contents')
    <!-- top tiles -->
    <div class="row tile_count">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-sitemap"></i> Total Repositories</span>
            <div class="count">{{ $repos_count or 0 }}</div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Total Developers</span>
            <div class="count">{{ $developers_count or 0 }}</div>
        </div>
    </div>
    <!-- /top tiles -->

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Google Analytics <small>Last 31 days</small></h2>
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
                                        <td class="fs15 fw700 text-right">{{ $item['sessions'] }}</td>
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
                                        <td class="fs15 fw700 text-right">{{ $item['pageViews'] }}</td>
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
                                        <td class="fs15 fw700 text-right">{{ $item['pageViews'] }}</td>
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
