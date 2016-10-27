@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Link Click ({{ $clicks->total() }})</h3>
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
                                <th>#</th>
                                <th>Target</th>
                                <th>Referer</th>
                                <th>IP</th>
                                <th>User-Agent</th>
                                <th>点击时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clicks as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td><a href="{{ $item->target }}" target="_blank">{{ $item->target }}</a></td>
                                    <td><a href="{{ $item->referer }}" target="_blank">{{ $item->referer }}</a></td>
                                    <td>{{ $geo_ip[$item->ip] ? $item->ip . ' (' . $geo_ip[$item->ip] .')' : $item->ip }}</td>
                                    <td>{{ $item->user_agent }}</td>
                                    <td>{{ $item->clicked_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $clicks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
