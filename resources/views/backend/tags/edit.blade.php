@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tag: {{ $tag->name }}</h3>
            </div>
            <form class="form-horizontal" method="POST" action="/backend/tags/{{ $tag->id }}">
                {!! csrf_field()  !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name', $tag->name) }}">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <a href="/backend/tags" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection