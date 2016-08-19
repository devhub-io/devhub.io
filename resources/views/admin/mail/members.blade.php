@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Subscriber</h3>
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
                                <th>name</th>
                                <th>address</th>
                                <th>subscribed</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($members as $item)
                                <tr>
                                    <td>{{ $item->name or '-' }}</td>
                                    <td>{{ $item->address or '' }}</td>
                                    <td>{{ $item->subscribed == 1 ? 'Subscribed' : 'Unsubscribed' }}</td>
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
                    <h4 class="modal-title">添加集合</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/subscribe') }}" method="post" enctype="multipart/form-data">
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
