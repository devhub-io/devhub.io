@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Developer</h3>
                <h4>Total: {{ $developer->total() }}</h4>
            </div>

            <div class="title_right">
                <div class="col-md-10 col-sm-8 col-xs-12 form-group pull-right top_search">
                    <div>
                        <a href="?sort=view_number&keyword={{ $keyword }}&type={{ $type }}" class="btn {{ $sort && $sort == 'view_number' ? 'btn-info' : 'btn-default' }}">浏览量↓</a>
                        <a href="?sort=followers&keyword={{ $keyword }}&type={{ $type }}" class="btn {{ $sort && $sort == 'followers' ? 'btn-info' : 'btn-default' }}">Followers↓</a>
                        <a href="?sort=fetched_at&keyword={{ $keyword }}&type={{ $type }}" class="btn {{ $sort && $sort == 'fetched_at' ? 'btn-info' : 'btn-default' }}">抓取时间↓</a>
                        <a href="?sort=analytics_at&keyword={{ $keyword }}&type={{ $type }}" class="btn {{ $sort && $sort == 'analytics_at' ? 'btn-info' : 'btn-default' }}">分析时间↓</a>
                        <a href="?type=User&keyword={{ $keyword }}" class="btn {{ $type && $type == 'User' ? 'btn-info' : 'btn-default' }}">User</a>
                        <a href="?type=Organization&keyword={{ $keyword }}" class="btn {{ $type && $type == 'Organization' ? 'btn-info' : 'btn-default' }}">Organization</a>
                        <a href="?empty=status&keyword={{ $keyword }}" class="btn {{ $empty && $empty == 'status' ? 'btn-info' : 'btn-default' }}">禁用</a>
                        <a href="{{ url('admin/developer') }}" class="btn btn-warning">清除</a>
                    </div>
                    <form action="" method="get">
                        <div class="input-group">
                            <input class="form-control" placeholder="Search for..." type="text" name="keyword" value="{{ $keyword }}">
                            <span class="input-group-btn">
                              <button class="btn btn-default" type="submit">Go!</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a href="{{ url('admin/developer/enable?id=' . $ids) }}" title="当前页全部启用"><i class="fa fa-check-square"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        {{ $developer->appends(['keyword' => $keyword, 'sort' => $sort, 'empty' => $empty])->links() }}
                        <!-- start project list -->
                        <table class="table table-striped projects">
                            <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 12%">名称</th>
                                <th>类型</th>
                                <th>图片</th>
                                <th>统计</th>
                                <th>状态</th>
                                <th style="width: 12%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($developer as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <b>{{ $item->login }}</b>
                                    <br>
                                    <small>抓取于 {{ $item->fetched_at }}</small>
                                    <br>
                                    <small>分析于 {{ $item->analytics_at }}</small>
                                </td>
                                <td>{{ $item->type }}</td>
                                <td><img src="{{ $item->avatar_url }}" alt="" width="100"></td>
                                <td>
                                    <ul class="list-inline">
                                        <li title="followers">
                                            <i class="fa fa-users"></i> {{ $item->followers }}
                                        </li>
                                        <li title="following">
                                            <i class="fa fa-user"></i> {{ $item->following }}
                                        </li>
                                        <li title="public_repos">
                                            <i class="fa fa-sitemap" ></i> {{ $item->public_repos }}
                                        </li>
                                        <li title="public_gists">
                                            <i class="fa fa-file"></i> {{ $item->public_gists }}
                                        </li>|
                                        <li title="Pageviews">
                                            <i class="glyphicon glyphicon-modal-window"></i> {{ $item->view_number }}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    @if($item->status == 1)
                                    <a href="{{ url("admin/developer/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                    @else
                                    <a href="{{ url("admin/developer/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('developer', [$item->login]) }}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-home"></i></a>
                                    <a href="{{ url("admin/developer/$item->id/history") }}" class="btn btn-info btn-xs" target="_blank" title="修改历史"><i class="fa fa-history"></i></a>
                                    <a href="{{ $item->html_url }}" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-github"></i></a>
                                    <a href="{{ url("admin/developer/$item->id/fetch") }}" class="btn btn-warning btn-xs" title="抓取"><i class="fa fa-feed"></i>  </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->
                        {{ $developer->appends(['keyword' => $keyword, 'sort' => $sort, 'empty' => $empty, 'type' => $type])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
