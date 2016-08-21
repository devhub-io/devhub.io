@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Repositories</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
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
                                <th style="width: 20%">操作</th>
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
                                </td>
                                <td><img src="{{ image_url($item->image, ['w' => 100]) }}" alt=""></td>
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
                                    <a href="{{ url('repos', [$item->slug]) }}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-home"></i> 前台展示 </a>
                                    <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-feed"></i> 抓取 </a>
                                    <a href="{{ url("admin/repos/$item->id/history") }}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-history"></i> 修改记录</a>
                                    <a href="{{ url("admin/repos/{$item->id}/edit") }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> 修改 </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->
                        {{ $repos->appends(['keyword' => $keyword])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
