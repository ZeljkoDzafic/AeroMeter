@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Config: {{ $config->name }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/configs/{{ $config->id }}">
                {!! csrf_field()  !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Key</label>
                        <div class="col-sm-10">
                            <input name="key" type="text" class="form-control" placeholder="Key" value="{{ old('key', $config->key) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Value</label>
                        <div class="col-sm-10">
                            <input name="value" type="text" class="form-control" placeholder="Value" value="{{ old('value', $config->value) }}">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/configs" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection