@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Import: {{ $station->name }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/stations/{{ $station->id }}/import" enctype="multipart/form-data">
                {!! csrf_field()  !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">File</label>
                        <div class="col-sm-10">
                            <input name="file" type="file" class="form-control" placeholder="File">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/stations/{{ $station->id }}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection