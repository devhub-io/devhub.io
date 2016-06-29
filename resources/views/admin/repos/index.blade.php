@extends('layouts.admin')

@section('contents')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Repositories</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input class="form-control" placeholder="Search for..." type="text">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                    </div>
                </div>
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
                                <th style="width: 20%">Project Name</th>
                                <th>Team Members</th>
                                <th>Project Progress</th>
                                <th>Status</th>
                                <th style="width: 20%">#Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($repos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <a>{{ $item->title }}</a>
                                    <br>
                                    <small>Fetched {{ $item->fetched_at }}</small>
                                </td>
                                <td>
                                    <ul class="list-inline">
                                        <li>
                                            <img src="images/user.png" class="avatar" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="images/user.png" class="avatar" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="images/user.png" class="avatar" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="images/user.png" class="avatar" alt="Avatar">
                                        </li>
                                    </ul>
                                </td>
                                <td class="project_progress">
                                    <div class="progress progress_sm">
                                        <div aria-valuenow="56" style="width: 57%;" class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>
                                    </div>
                                    <small>57% Complete</small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-xs">Success</button>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
                                    <a href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                </td>
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