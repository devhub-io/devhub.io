@extends('layouts.admin')

@section('contents')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Images</h3>
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
                            <th>URL</th>
                            <th>Slug</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($images as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <th scope="row"><img src="{{ image_url($item->slug, ['w' => 100]) }}" alt=""></th>
                            <td>{{ $item->slug }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a class="btn btn-danger btn-xs" href="{{ url("admin/images/{$item->id}/delete") }}">删除</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $images->links() }}
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
                <h4 class="modal-title">上传图片</h4>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/images') }}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="image">图片</label>
                        <input type="file" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-info">上传</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
