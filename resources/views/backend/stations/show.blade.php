@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Station: {{ $station->name }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $station->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $station->description }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Unique ID</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $station->unique_id }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Position</label>
                        <div class="col-sm-10">
                            <div id="gmap" style="height: 250px;"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{!! join(', ', array_map(function($tag) {
                                return '<a href="/backend/stations?tag_id='.$tag['id'].'">'.$tag['name'].'</a>';
                            }, $station->tags()->get()->all())) !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">User</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><a href="/backend/users/{{ $station->user->id }}">{{ $station->user->id }} ({{ $station->user->email }})</a></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Aerometrics</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><a href="/backend/stations/{{ $station->id }}/aerometrics">Aerometrics</a></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Latest aerometrics</label>
                        <div class="col-sm-10">
                            @if($aerometric)
                            <div class="row">
                                @foreach(array_keys(config('aerometrics.properties')) as $property)
                                    <div class="col-xs-3 text-center">
                                        <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="{{ $aerometric->{$property} }}" data-width="120" data-height="120" data-fgColor="{{ config('aerometrics.properties.' . $property . '.color') }}">
                                        <div class="knob-label">{{ strtoupper(str_replace('_', ' ', $property)) }}</div>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <form role="form" method="POST" action="{{ url('/backend/stations/' . $station->id) }}">
                                {!! method_field('DELETE') !!}
                                {!! csrf_field() !!}
                                <a href="/backend/stations" class="btn btn-default">Back</a>
                                <a href="/backend/stations/{{ $station->id }}/edit" class="btn btn-success">Edit</a>
                                <button class="btn btn-danger" type="submit">Delete</button>
                                <a href="/backend/stations/{{ $station->id }}/import" class="btn btn-primary">Import</a>
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
    <script src="//maps.google.com/maps/api/js"></script>
    <script src="{{ asset('plugins/knob/jquery.knob.js')  }}"></script>
    <script src="{{ asset('js/gmaps.js')  }}"></script>
    <script type="text/javascript">
        $(function() {
            var gmaps = new GMaps({
                div: '#gmap',
                lat: {{ $station->lat }},
                lng: {{ $station->lng }}
            });
            gmaps.addMarker({
                lat: {{ $station->lat }},
                lng: {{ $station->lng }}

            });
            $(".knob").knob({
                readOnly: true
            });
        });
    </script>
@endsection