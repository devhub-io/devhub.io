@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>网站维护</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            @foreach($revisions_num as $item)
                <div class="col-md-5"><h2>Revisions Number: <span style="font-size: 32px;">{{ $item->num }}</span> row</h2></div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-5"><h2>Disk free space: <span style="font-size: 32px;">{{ $df }}</span> Mb</h2></div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-md-5">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Limit</th>
                        <th>Remaining</th>
                        <th>Reset</th>
                        <th>Use</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>GithubUpdate</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
