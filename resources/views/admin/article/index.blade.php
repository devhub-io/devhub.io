@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Articles</h3>
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
                                <th style="width: 12%">标题</th>
                                <th>内容</th>
                                <th>标识</th>
                                <th>URL</th>
                                <th>统计</th>
                                <th>状态</th>
                                <th style="width: 20%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articles as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <b>{{ $item->title }}</b>
                                        <br>
                                        <small>抓取于 {{ $item->fetched_at }}</small>
                                    </td>
                                    <td>{{ $item->content }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td>{{ $item->url }}</td>
                                    <td>
                                        <ul class="list-inline">
                                            <li title="read">
                                                <i class="glyphicon glyphicon-star" ></i> {{ $item->read_number }}
                                            </li>
                                            <li title="up">
                                                <i class="glyphicon glyphicon-wrench"></i> {{ $item->up_number }}
                                            </li>
                                            <li title="down">
                                                <i class="glyphicon glyphicon-duplicate"></i> {{ $item->down_number }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        @if($item->is_enable)
                                            <a href="{{ url("admin/articles/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                        @else
                                            <a href="{{ url("admin/articles/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url("admin/articles/$item->id/fetch") }}" class="btn btn-warning btn-xs"><i class="fa fa-feed"></i> 抓取 </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->
                        {{ $articles->appends(['keyword' => $keyword])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
