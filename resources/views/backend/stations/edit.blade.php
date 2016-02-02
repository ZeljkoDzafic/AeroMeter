@extends('layouts.backend')

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Station: {{ $station->name }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/stations/{{ $station->id }}">
                {!! csrf_field()  !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name', $station->name) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input name="description" type="text" class="form-control" placeholder="Description" value="{{ old('description', $station->description) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Unique ID</label>
                        <div class="col-sm-10">
                            <input name="unique_id" type="text" class="form-control" placeholder="Unique ID" value="{{ old('unique_id', $station->unique_id) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10">
                            <select name="tags[]" class="form-control select2" multiple="multiple" data-placeholder="Select tags" style="width: 100%;">
                                @foreach($tags as $id => $name)
                                    <option id="{{ $name }}" @if(in_array($name, $selected_tags)) selected="selected" @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Position</label>
                        <div class="col-sm-10">
                            <div id="gmap" style="height: 250px;"></div>
                            <input name="lat" type="hidden" value="{{ old('lat', $station->lat) }}" />
                            <input name="lng" type="hidden" value="{{ old('lng', $station->lng) }}" />
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/stations" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="//maps.google.com/maps/api/js"></script>
    <script src="{{ asset('js/gmaps.js')  }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var gmaps = new GMaps({
                div: '#gmap',
                lat: {{ old('lat', $station->lat) }},
                lng: {{ old('lng', $station->lng) }}
            });
            gmaps.addMarker({
                lat: {{ old('lat', $station->lat) }},
                lng: {{ old('lng', $station->lng) }},
                draggable: true,
                dragend: function(e) {
                    $('input[name="lat"]').val(e.latLng.lat());
                    $('input[name="lng"]').val(e.latLng.lng());
                }
            });
            $('.select2').select2({
                tags: true,
                tokenSeparators: [",", " "]
            }).on("change", function(e) {
                var isNew = $(this).find('[data-select2-tag="true"]');
                if(isNew.length){
                    isNew.replaceWith('<option selected value="'+isNew.val()+'">'+isNew.val()+'</option>');
                }
            });
        });
    </script>
@endsection