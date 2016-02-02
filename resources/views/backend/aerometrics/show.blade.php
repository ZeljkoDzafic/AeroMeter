@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Aerometric: {{ $aerometric->id }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Station</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><a href="/backend/stations/{{ $station->id }}">Station</a></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Aerometric</label>
                        <div class="col-sm-10">
                            @foreach(array_keys(config('aerometrics.properties')) as $property)
                                <div class="col-xs-3 text-center">
                                    <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="{{ $aerometric->{$property} }}" data-width="120" data-height="120" data-fgColor="#00c0ef">
                                    <div class="knob-label">{{ strtoupper(str_replace('_', ' ', $property)) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Created at</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $station->created_at }}</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <form role="form" method="POST" action="{{ url('/backend/stations/' . $station->id . '/aerometrics/' . $aerometric->id) }}">
                                {!! method_field('DELETE') !!}
                                {!! csrf_field() !!}
                                <a href="/backend/stations/{{ $station->id }}/aerometrics" class="btn btn-default">Back</a>
                                <a href="/backend/stations/{{ $station->id }}/aerometrics/{{ $aerometric->id }}/edit" class="btn btn-success">Edit</a>
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

@section('scripts')
    <script src="{{ asset('plugins/knob/jquery.knob.js')  }}"></script>
    <script type="text/javascript">
        $(function() {
            $(".knob").knob({
                readOnly: true
            });
        });
    </script>
@endsection