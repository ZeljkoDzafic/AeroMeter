@extends('layouts.backend')

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tags</h3>
            </div>
            <div class="box-body">
                <p>
                    <a href="/backend/tags/create" class="btn btn-primary"><i class="fa fa-plus-circle"></i> New</a>
                </p>
                <table id="table" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Stations</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td>{{ $tag->name }}</td>
                            <td><a href="/backend/stations?tag_id={{ $tag->id }}">Stations</a></td>
                            <td>
                                <form role="form" method="POST" action="{{ url('/backend/tags/' . $tag->id) }}">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}
                                    <a href="/backend/tags/{{ $tag->id }}" class="btn btn-xs btn-default">Show</a>
                                    <a href="/backend/tags/{{ $tag->id }}/edit" class="btn btn-xs btn-success">Edit</a>
                                    <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>There are no tags.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Stations</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                </table>
                <p>
                    <a href="/backend/tags/create" class="btn btn-primary"><i class="fa fa-plus-circle"></i> New</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function() {
            $('#table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
@endsection