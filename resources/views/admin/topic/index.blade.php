@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Topics</h3>
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
                            <li><a href="{{ url('topics') }}" target="_blank"><i class="fa fa-home"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th width="10%">Topic</th>
                                <th>Explain</th>
                                <th width="10%">更新时间</th>
                                <th width="15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topics as $item)
                                <tr>
                                    <td>{{ $item->topic }}</td>
                                    <td>{{ $item->explain }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="javascript:void(0);" v-on:click="editTopic('{{ $item->topic }}')">修改</a>
                                        <a class="btn btn-danger btn-xs" href="javascript:confirmDelete('{{ url("admin/topics/{$item->topic}/delete") }}')">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $topics->links() }}
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
                    <h4 class="modal-title">添加主题</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/topics') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title">主题</label>
                            <input type="text" id="topic" name="topic" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">描述</label>
                            <textarea name="explain" id="explain" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info">添加</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">修改主题</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/topics') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="type" value="edit">
                        <div class="form-group">
                            <label for="title">主题</label>
                            <input type="text" id="topic" name="topic" class="form-control" v-model="topic">
                        </div>
                        <div class="form-group">
                            <label for="description">描述</label>
                            <textarea name="explain" id="explain" cols="30" rows="10" class="form-control" v-model="explain"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info">修改</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script src="https://vuejs.org/js/vue.min.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                topic: '',
                explain: ''
            },
            methods: {
                editTopic: function (topic) {
                    $.get('{{ url('admin/topic') }}', {topic: topic}, function (res) {
                        app.topic = res.topic;
                        app.explain = res.explain;
                        $('#editModal').modal('show');
                    });
                }
            }
        });

        function confirmDelete(url) {
            if(confirm('确实删除?')){
                location.href = url;
            }
        }
    </script>
@endsection
