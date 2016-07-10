@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>修改Repos</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form class="form-horizontal form-label-left" method="post" action="{{ url('admin/repos/'.$repository->id) }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">标题</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="title" value="{{ $repository->title }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">分类</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control" name="category_id">
                                        <option value="0">--</option>
                                        @foreach($categories as $item)
                                            <option value="{{ $item->id }}" @if($repository->category_id == $item->id) selected @endif>@if($item->parent_id > 0) -- @endif{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">图片</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="hidden" name="image" value="{{ $repository->image }}" id="image_id">
                                    <img src="{{ image_url($repository->image, ['w' => 100]) }}" alt="" id="image_url">
                                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#imagesModal">选择图片</button>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                    <button class="btn btn-primary" type="button" onclick="location.history.back()">返回</button>
                                    <button class="btn btn-success" type="submit">更新</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="imagesModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">选择图片</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>URL</th>
                            <th>创建时间</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($images as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <th scope="row"><img src="{{ image_url($item->slug, ['w' => 100]) }}" alt=""></th>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <button class="btn btn-info selectImage" type="button" data-id="{{ $item->id }}" data-url="{{ image_url($item->slug, ['w' => 100]) }}">选择</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $images->links() }}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script>
        $('.selectImage').on('click', function () {
            $('#image_id').val($(this).data('id'));
            $('#image_url').attr('src', $(this).data('url'));
            $('#imagesModal').modal('hide');
        });
    </script>
@endsection
