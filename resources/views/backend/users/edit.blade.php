@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">User: {{ $user->email }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/users/{{ $user->id }}">
                {!! csrf_field()  !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input name="email" type="text" class="form-control" placeholder="Email" value="{{ old('email', $user->email) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input name="password" type="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password Confirmation</label>
                        <div class="col-sm-10">
                            <input name="password_confirmation" type="password" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/users" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection