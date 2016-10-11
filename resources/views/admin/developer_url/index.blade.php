@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Developer URL</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-8 col-sm-10 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a href="javascript:void(0);" data-toggle="modal" data-target="#addAllModal" title="批量添加链接"><i class="fa fa-plus-circle"></i></a></li>
                            <li><a href="{{ url('admin/fetch_all_developer_url') }}" title="添加到队列"><i class="fa fa-send"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>URL</th>
                                <th>添加时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($urls as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                                    <td>{{ $item->created_at }}</td>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="addAllModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">批量添加链接</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/all_developer_url') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="url">链接</label>
                            <textarea id="url" name="url" class="form-control" rows="20"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
