@extends('layouts.admin')

@section('styles')
    <link href="{{ cdn_asset('components/select2/select2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ cdn_asset('components/select2-bootstrap-css/select2-bootstrap.min.css') }}">
@endsection

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{ $collection->title }} - Repos</h3>
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
                            @foreach($repos as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->repos->title }}</td>
                                    <td>{{ $item->repos->slug }}</td>
                                    <td><img src="{{ image_url($item->repos->image, ['w' => 100]) }}" alt=""></td>
                                    <td>{{ $item->sort }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        @if($item->is_enable == 1)
                                            <a href="{{ url("admin/collections/{$id}/repos/{$item->id}/change_enable") }}" class="btn btn-success btn-xs" title="点击禁用">启用</a>
                                        @else
                                            <a href="{{ url("admin/collections/{$id}/repos/{$item->id}/change_enable") }}" class="btn btn-danger btn-xs" title="点击启用">禁用</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="{{ url("admin/collections/{$id}/repos/{$item->repos_id}/delete") }}">移除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $repos->links() }}
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
                    <h4 class="modal-title">添加资源到集合</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url("admin/collections/{$id}/repos") }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="repos_id">资源</label>
                            <select name="repos_id" id="repos_id" class="form-control" style="width: 500px;">
                                @foreach($all_repos as $item)
                                <option value="{{ $item->id }}">{{ $item->slug }} | {{ $item->title }}</option>
                                @endforeach
                            </select>
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

@section('scripts')
    <script src="{{ cdn_asset('components/select2/select2.min.js') }}"></script>
    <script>
        $('#repos_id').select2();
    </script>
@endsection
