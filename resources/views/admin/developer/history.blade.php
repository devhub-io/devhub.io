@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{ $developer->login }} - Revision History</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- start project list -->
                        <table class="table table-striped projects">
                            <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th>user</th>
                                <th>key</th>
                                <th>old value</th>
                                <th>new value</th>
                                <th>updated at</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->userResponsible() ? $item->userResponsible()->name : '-' }}</td>
                                    <td>{{ $item->key }}</td>
                                    <td>{{ $item->old_value }}</td>
                                    <td>{{ $item->new_value }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- end project list -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
