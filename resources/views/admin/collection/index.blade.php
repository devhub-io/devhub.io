@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Collections</h3>
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
                                <th>标题</th>
                                <th>标识</th>
                                <th>图片</th>
                                <th>排序</th>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($collections as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td><img src="{{ cdn_asset($item->image) }}" alt="" width="100" height="100"></td>
                                    <td>{{ $item->sort }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        @if($item->is_enable == 1)
                                            <a href="{{ url("admin/collections/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                        @else
                                            <a href="{{ url("admin/collections/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="{{ url("admin/collections/{$item->id}/repos") }}">集合资源管理</a>
                                        <a href="{{ url('collection', [$item->slug]) }}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-home"></i> 前台展示 </a>
                                        <a class="btn btn-success btn-xs" href="{{ url("admin/collections/{$item->id}/cover") }}">生成封面</a>
                                        <a class="btn btn-default btn-xs" href="javascript:void(0);">修改</a>
                                        <a class="btn btn-danger btn-xs" href="javascript:confirmDelete('{{ url("admin/collections/{$item->id}/delete") }}')">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $collections->links() }}
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
                    <h4 class="modal-title">添加集合</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/collections') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title">标题</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="slug">标识</label>
                            <input type="text" id="slug" name="slug" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="sort">排序</label>
                            <input type="number" id="sort" name="sort" class="form-control" value="0">
                        </div>
                        <input type="hidden" name="is_enable" value="0">
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script src="{{ cdn_asset('components/knockout/dist/knockout.js') }}"></script>
    <script>
        var viewModel = {
            title: ko.observable(''),
            url: ko.observable(''),
            category: ko.observable(''),
            level: ko.observable(1),
            description: ko.observable(''),
            icon: ko.observable(''),
            sort: ko.observable(0),

            editSite: function (id) {
                $.get('{{ url('admin/site') }}', {id: id}, function (res) {
                    console.log(res);
                });

                $('#editModal').modal('show');
            },

            saveSite: function () {
                console.log('11')
            }
        };

        ko.applyBindings(viewModel);

        function confirmDelete(url) {
            if(confirm('确实删除?')){
                location.href = url;
            }
        }
    </script>
@endsection
