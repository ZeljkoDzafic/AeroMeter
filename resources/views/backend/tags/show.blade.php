@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tag: {{ $tag->name }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $tag->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Stations</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><a href="/backend/stations?tag_id={{ $tag->id }}">Stations</a></p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <form role="form" method="POST" action="{{ url('/backend/tags/' . $tag->id) }}">
                                {!! method_field('DELETE') !!}
                                {!! csrf_field() !!}
                                <a href="/backend/tags" class="btn btn-default">Back</a>
                                <a href="/backend/tags/{{ $tag->id }}/edit" class="btn btn-success">Edit</a>
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