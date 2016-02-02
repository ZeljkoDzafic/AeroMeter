@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div id="gmap" style="height: 250px;"></div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#chart-tab" aria-controls="chart-tab" role="tab" data-toggle="tab">Chart</a></li>
                            <li role="presentation"><a href="#table-tab" aria-controls="table-tab" role="tab" data-toggle="tab">Table</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="chart-tab">
                                <div class="chart">
                                    <canvas id="lineChart" style="height:200px"></canvas>
                                </div>
                                <div id="legend">

                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="table-tab">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Account</div>

                    <div class="panel-body">
                        @if(request()->user())
                            <a href="/backend" class="btn btn-primary">
                                <i class="fa fa-btn fa-tachometer"></i>Backend
                            </a>
                            <a href="/logout" class="btn btn-default">
                                <i class="fa fa-btn fa-arrow-circle-right"></i>Logout
                            </a>
                        @else
                        <form class="form" role="form" method="POST" action="{{ url('/login') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-12 control-label">E-Mail Address</label>

                                <div class="col-md-12">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-12 control-label">Password</label>

                                <div class="col-md-12">
                                    <input type="password" class="form-control" name="password">
                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Login
                                    </button>
                                    <a href="/register/" class="btn btn-default">
                                        <i class="fa fa-btn fa-user"></i>Register
                                    </a>

                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="//maps.google.com/maps/api/js"></script>
    <script src="{{ asset('js/gmaps.js')  }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.min.js')  }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.Scatter.min.js')  }}"></script>
    <script type="text/javascript">
        $(function() {
            var _id = {{ config('aero.default_marker') }};
            var gmaps = new GMaps({
                div: '#gmap',
                lat: {{ config('aero.lat') }},
                lng: {{ config('aero.lng') }},
                zoom: {{ config('aero.zoom_level') }}
            });
            @foreach($stations as $station)
            gmaps.addMarker({
                id: {{ $station->id }},
                lat: {{ $station->lat }},
                lng: {{ $station->lng }},
                click: function(e) {
                    _id = e.id;
                    updateData();
                }
            });
            @endforeach


            var colors = [];
            @foreach(array_keys(config('aerometrics.properties')) as $property)
            colors['{{ $property }}'] = '{{ config('aerometrics.properties.' . $property . '.color') }}';
            @endforeach

            function updateData() {
                var table = $('#station_table');
                $.ajax({
                    url: '/api/stations/' + _id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (aerometrics) {
                        var data = [];
                        var dates = [];
                        var values = [];
                        var properties = [];

                        var header = aerometrics[0];
                        for (var property in header) {
                            if (header.hasOwnProperty(property) && property != 'created_at') {
                                data.push({
                                    label: property,
                                    strokeColor: colors[property],
                                    data: []
                                });
                                values[property] = [];
                                properties.push(property);
                            }
                        }

                        aerometrics.forEach(function(aerometric) {
                            dates.push(aerometric['created_at']);
                            for (var property in aerometric) {
                                if (aerometric.hasOwnProperty(property) && property != 'created_at') {
                                    values[property][aerometric['created_at']] = aerometric[property];
                                }
                            }
                        });

                        aerometrics.forEach(function(aerometric) {
                            for (var property in aerometric) {
                                if (aerometric.hasOwnProperty(property) && property != 'created_at') {
                                    data.forEach(function(dataset) {
                                        if(dataset['label'] == property) {
                                            dataset['data'].push({
                                                x: new Date(aerometric['created_at']),
                                                y: aerometric[property]
                                            });
                                        }
                                    });
                                }
                            }
                        });

                        var chartCanvas = $("#lineChart").get(0).getContext("2d");
                        var chart = new Chart(chartCanvas).Scatter(data, {
                            bezierCurve: true,
                            showTooltips: true,
                            scaleShowHorizontalLines: true,
                            scaleShowLabels: true,
                            scaleType: "date",

                            responsive: true,

                            tooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%=argLabel%>; <%=valueLabel%>",
                            multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%=argLabel%>; <%=valueLabel%>",

                            legendTemplate: "<ul class=\"chart-legend\"><%for(var i=0;i<datasets.length;i++){%><li><span class=\"chart-legend-marker\" style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%=datasets[i].label%></li><%}%></ul>"
                        });

                        $("#legend").html(chart.generateLegend());

                        var tableHtml = '<table class="table">';
                        tableHtml += '<tr>';
                        tableHtml += '<th></th>';
                        dates.forEach(function(date) {
                           tableHtml += '<th><small>' + date + '</small></th>';
                        });
                        tableHtml += '</tr>';
                        properties.forEach(function(property) {
                            tableHtml += '<tr>';
                            tableHtml += '<th>' + property + '</th>';
                            dates.forEach(function(date) {
                                tableHtml += '<td>' + values[property][date] + '</td>';
                            });
                            tableHtml += '</tr>';
                        });

                        tableHtml += '</table>';
                        $('#table-tab').html(tableHtml);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            updateData();
        });
    </script>
@endsection