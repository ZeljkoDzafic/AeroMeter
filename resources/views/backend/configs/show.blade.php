@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Config: {{ $config->name }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Key</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $config->key }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Value</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $config->value }}</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <form role="form" method="POST" action="{{ url('/backend/configs/' . $config->id) }}">
                                {!! method_field('DELETE') !!}
                                {!! csrf_field() !!}
                                <a href="/backend/configs" class="btn btn-default">Back</a>
                                <a href="/backend/configs/{{ $config->id }}/edit" class="btn btn-success">Edit</a>
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection