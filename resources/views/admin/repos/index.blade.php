@extends('layouts.admin')

@section('contents')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Repositories</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input class="form-control" placeholder="Search for..." type="text">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                    </div>
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
                                <th style="width: 15%">名称</th>
                                <th>分类</th>
                                <th>标识</th>
                                <th>统计</th>
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
                                <td>{{ $item->category->title or '-' }}</td>
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
                                    @if($item->status == 1)
                                    <button type="button" class="btn btn-success btn-xs">启用</button>
                                    @else
                                    <button type="button" class="btn btn-danger btn-xs">禁用</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('repos', [$item->slug]) }}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-home"></i> 前台展示 </a>
                                    <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-feed"></i> 抓取 </a>
                                    <a href="{{ url("admin/repos/{$item->id}/edit") }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> 修改 </a>
                                    <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 禁用 </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
