@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>用户</h3>
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
                                <th>名称</th>
                                <th>邮箱</th>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        @if($item->is_enable == 1)
                                            <a href="{{ url("admin/user/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                        @else
                                            <a href="{{ url("admin/user/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-xs edit-password" href="javascript:void(0);" data-id="{{ $item->id }}">修改密码</a>
                                        <a href="{{ url('collection', [$item->slug]) }}" target="_blank" class="btn btn-primary btn-xs">权限管理</a>
                                        <a class="btn btn-default btn-xs" href="javascript:void(0);">修改</a>
                                        @if($item->id != 1)
                                            <a class="btn btn-danger btn-xs" href="javascript:confirmDelete('{{ url("admin/user/{$item->id}/delete") }}')">删除</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
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
                    <h4 class="modal-title">添加用户</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/user') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="name">名称</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">邮箱</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">密码</label>
                            <input type="password" id="password" name="password" class="form-control" value="" required>
                        </div>
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="passwordModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">修改密码</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/user/password') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="0" id="user_id">
                        <div class="form-group">
                            <label for="password">新密码</label>
                            <input type="password" id="password" name="password" class="form-control" value="" required>
                        </div>
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script>
        function confirmDelete(url) {
            if (confirm('确实删除?')) {
                location.href = url;
            }
        }

        $('.edit-password').on('click', function () {
            var user_id = $(this).data('id');
            $('#user_id').val(user_id);
            $('#passwordModal').modal('show');
        });
    </script>
@endsection
