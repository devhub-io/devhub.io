@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Repositories</h3>
            </div>

            <div class="title_right">
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-right top_search">
                    <div>
                        <a href="?sort=view_number&keyword={{ $keyword }}" class="btn {{ $sort && $sort == 'view_number' ? 'btn-info' : 'btn-default' }}">浏览量↓</a>
                        <a href="?sort=stargazers_count&keyword={{ $keyword }}" class="btn {{ $sort && $sort == 'stargazers_count' ? 'btn-info' : 'btn-default' }}">收藏量↓</a>
                        <a href="?sort=fetched_at&keyword={{ $keyword }}" class="btn {{ $sort && $sort == 'fetched_at' ? 'btn-info' : 'btn-default' }}">抓取时间↓</a>
                        <a href="?sort=analytics_at&keyword={{ $keyword }}" class="btn {{ $sort && $sort == 'analytics_at' ? 'btn-info' : 'btn-default' }}">分析时间↓</a>
                        <a href="?empty=category_id&keyword={{ $keyword }}" class="btn {{ $empty && $empty == 'category_id' ? 'btn-info' : 'btn-default' }}">未设分类</a>
                        <a href="?empty=status&keyword={{ $keyword }}" class="btn {{ $empty && $empty == 'status' ? 'btn-info' : 'btn-default' }}">禁用</a>
                        <a href="{{ url('admin/repos') }}" class="btn btn-warning">清除</a>
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
                            <li><a href="{{ url('admin/repos/reindex') }}" title="重构搜索索引"><i class="fa fa-refresh"></i></a></li>
                            <li><a href="{{ url('admin/repos/enable?id=' . $ids) }}" title="当前页全部启用"><i class="fa fa-check-square"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- start project list -->
                        <table class="table table-striped projects">
                            <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 12%">名称</th>
                                <th>图片</th>
                                <th>分类</th>
                                <th>语言</th>
                                <th>标识</th>
                                <th>统计</th>
                                <th>推荐</th>
                                <th>状态</th>
                                <th style="width: 10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($repos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <b>{{ $item->title }}</b>
                                    <br>
                                    <small>抓取于 {{ $item->fetched_at }}</small>
                                    <br>
                                    <small>分析于 {{ $item->analytics_at }}</small>
                                </td>
                                <td><img src="{{ $item->image > 0 ? image_url($item->image, ['w' => 100]) : '' }}" alt=""></td>
                                <td>{{ $item->category->title or '-' }}</td>
                                <td>{{ $item->language }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>
                                    <ul class="list-inline">
                                        <li title="stargazers">
                                            <i class="glyphicon glyphicon-star" ></i> {{ $item->stargazers_count }}
                                        </li>
                                        <li title="forks">
                                            <i class="glyphicon glyphicon-wrench"></i> {{ $item->forks_count }}
                                        </li>
                                        <li title="watchers">
                                            <i class="glyphicon glyphicon-duplicate"></i> {{ $item->watchers_count }}
                                        </li>
                                        <li title="subscribers">
                                            <i class="glyphicon glyphicon-scale"></i> {{ $item->subscribers_count }}
                                        </li>
                                        <li title="issues">
                                            <i class="glyphicon glyphicon-adjust"></i> {{ $item->open_issues_count }}
                                        </li>|
                                        <li title="Pageviews">
                                            <i class="glyphicon glyphicon-modal-window"></i> {{ $item->view_number }}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    @if($item->is_recommend == 1)
                                        <a href="{{ url("admin/repos/{$item->id}/change_recommend") }}" class="btn btn-success btn-xs" title="点击禁用推荐">推荐</a>
                                    @else
                                        <a href="{{ url("admin/repos/{$item->id}/change_recommend") }}" class="btn btn-info btn-xs" title="点击启用推荐">-</a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 1)
                                    <a href="{{ url("admin/repos/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                    @else
                                    <a href="{{ url("admin/repos/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('repos', [$item->slug]) }}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-home"></i></a>
                                    <a href="{{ url("admin/repos/$item->id/history") }}" class="btn btn-info btn-xs" target="_blank" title="修改历史"><i class="fa fa-history"></i></a>
                                    <a href="{{ url("admin/repos/$item->id/fetch") }}" class="btn btn-warning btn-xs" title="抓取"><i class="fa fa-feed"></i>  </a>
                                    <a href="{{ url("admin/repos/$item->id/edit") }}" class="btn btn-info btn-xs" title="修改"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->
                        {{ $repos->appends(['keyword' => $keyword, 'sort' => $sort, 'empty' => $empty])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
