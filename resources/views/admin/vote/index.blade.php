@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Vote ({{ $vote->total() }})</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                                <th>Repos</th>
                                <th>Vote</th>
                                <th>IP</th>
                                <th>User-Agent</th>
                                <th>添加时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vote as $item)
                                <tr>
                                    <th scope="row"><a href="{{ url('repos', [$item->repos->slug]) }}" target="_blank">{{ $item->repos->title }}</a></th>
                                    <td>
                                        <ul class="list-inline">
                                            <li title="reliable">
                                                <i class="glyphicon glyphicon-star" ></i> {{ $item->reliable }}
                                            </li>
                                            <li title="recommendation">
                                                <i class="glyphicon glyphicon-wrench"></i> {{ $item->recommendation }}
                                            </li>
                                            <li title="documentation">
                                                <i class="glyphicon glyphicon-duplicate"></i> {{ $item->documentation }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td>{{ $item->ip }}</td>
                                    <td>{{ $item->user_agent }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $vote->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
