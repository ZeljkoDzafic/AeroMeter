@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Aerometric: {{ $aerometric->id }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/stations/{{ $station->id }}/aerometrics/{{ $aerometric->id }}">
                {!! csrf_field()  !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="box-body">
                        @foreach(array_keys(config('aerometrics.properties')) as $property)
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{ strtoupper(str_replace('_', ' ', $property)) }}</label>
                                <div class="col-sm-10">
                                    <input name="{{ $property }}" type="text" class="form-control" placeholder="{{ strtoupper(str_replace('_', ' ', $property)) }}" value="{{ old($property, $aerometric->{$property}) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/stations/{{ $station->id }}/aerometrics" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection