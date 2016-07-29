@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>分类</h3>
                <a href="{{ url('admin/categories/create') }}" class="btn btn-info">创建分类</a>
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
                                <th>标题</th>
                                <th>标识</th>
                                <th>父级ID</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <th scope="row">{{ $category->id }}</th>
                                <td>{{ $category->title }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent_id }}</td>
                                <td>
                                    <a href="{{ url("admin/categories/{$category->id}/edit") }}" class="btn btn-default btn-xs">修改</a>
                                    <form action="{{ url("admin/categories/{$category->id}") }}" method="post" onsubmit="return confirmDelete()">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger btn-xs" type="submit">删除</button>
                                    </form>
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
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('确实删除?');
        }
    </script>
@endsection
