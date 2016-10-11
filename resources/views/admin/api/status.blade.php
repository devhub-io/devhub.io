@extends('layouts.admin')

@section('contents')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Api Status</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-5">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Limit</th>
                        <th>Remaining</th>
                        <th>Reset</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $rate_limits['resources']['core']['limit'] }}</td>
                        <td>{{ $rate_limits['resources']['core']['remaining'] }} ({{ round($rate_limits['resources']['core']['remaining'] / $rate_limits['resources']['core']['limit'] * 100) }}%)</td>
                        <td>{{ date('Y-m-d H:i:s', $rate_limits['resources']['core']['reset']) }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>{{ $rate_limits2['resources']['core']['limit'] }}</td>
                        <td>{{ $rate_limits2['resources']['core']['remaining'] }} ({{ round($rate_limits2['resources']['core']['remaining'] / $rate_limits2['resources']['core']['limit'] * 100) }}%)</td>
                        <td>{{ date('Y-m-d H:i:s', $rate_limits2['resources']['core']['reset']) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
