@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Please enter your token.</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('auth/2fa') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <label for="code" class="col-md-4 control-label">Authentication Code</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control" name="code" value="{{ old('email') }}" autocomplete="off">

                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
