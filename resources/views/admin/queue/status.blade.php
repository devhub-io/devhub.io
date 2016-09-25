@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Queues: {{ $queues_count }}</h3>
            </div>
        </div>

        <div class="clearfix"></div>


        <div class="row">
            <div class="col-md-8 col-sm-10 col-xs-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Payload</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($queues as $item)
                        <tr>
                            <td>{{ $item }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-sm-10 col-xs-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>connection</th>
                        <th>queue</th>
                        <th>payload</th>
                        <th>exception</th>
                        <th>failed_at</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($failed_jobs as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->connection }}</td>
                            <td>{{ $item->queue }}</td>
                            <td>{{ $item->payload }}</td>
                            <td>{{ substr($item->exception, 0, 250) }}</td>
                            <td>{{ $item->failed_at }}</td>
                            <td>
                                <a class="btn btn-danger btn-xs"
                                   href="{{ url("admin/failed_jobs/{$item->id}/delete") }}">删除</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $failed_jobs->links() }}
            </div>
        </div>
    </div>
@endsection
