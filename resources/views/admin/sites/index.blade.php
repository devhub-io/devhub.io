@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Sites</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a href="javascript:void(0);" data-toggle="modal" data-target="#uploadModal"><i class="fa fa-plus"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>分类</th>
                                <th>级别</th>
                                <th>图标</th>
                                <th>标题</th>
                                <th>URL</th>
                                <th>描述</th>
                                <th>排序</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sites as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>@lang("category.{$item->category}")</td>
                                    <td>{{ $item->level == 1 ? '推荐' : '友链' }}</td>
                                    <th scope="row"><img src="{{ asset($item->icon) }}" alt="" width="32" height="32"></th>
                                    <td>{{ $item->title }}</td>
                                    <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->sort }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="{{ url("admin/sites/{$item->id}/delete") }}">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">添加站点</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/sites') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title">标题</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" id="url" name="url" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="category">分类</label>
                            <select name="category" id="category" class="form-control">
                                @foreach($categories as $item)
                                <option value="{{ $item->slug }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="level">级别</label>
                            <select name="level" id="level" class="form-control">
                                <option value="1">推荐</option>
                                <option value="2">友链</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">描述</label>
                            <input type="text" id="description" name="description" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="icon">图标</label>
                            <input type="file" id="icon" name="icon">
                        </div>
                        <div class="form-group">
                            <label for="sort">排序</label>
                            <input type="number" id="sort" name="sort" class="form-control" value="0">
                        </div>
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
