@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>URL</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>URL</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($urls as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->url }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a href="{{ url("admin/url/{$item->id}/fetch") }}" class="btn btn-default btn-xs">抓取</a>
                                        <a class="btn btn-danger btn-xs" href="{{ url("admin/url/{$item->id}/delete") }}">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $urls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
