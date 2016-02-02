@extends('layouts.backend')

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker-bs3.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}" />
    <style>
        .chart-legend {
            list-style: none;
            padding: 0;
        }
        .chart-legend > li {
            float: left;
            margin-right: 10px;
        }
        .chart-legend-marker {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 5px;
        }
        #gmap {
            margin: 10px 0;
        }
        .content-wrapper {
            overflow: auto;
        }
    </style>
@endsection

@section('content')
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Dashboard</h3>
            </div>
            <div class="box-body">
                <!-- Date and time range -->
                <div class="form-group">
                    <div id="gmap" style="height: 250px;"></div>
                </div>
                <label>Range:</label>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-6 col-md-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <input type="text" class="form-control" id="range">
                            </div>
                            <label>Properties:</label>
                            <p>
                            @foreach(array_keys(config('aerometrics.properties')) as $property)
                                <label>
                                    <input type="checkbox" class="minimal" data-property="{{ $property }}" @if($property == 'temperature') checked @endif>
                                    {{ strtoupper(str_replace('_', ' ', $property)) }}
                                </label>
                            @endforeach
                            </p>
                        </div>
                        <div class="col-xs-6 col-md-2">
                            <button class="btn btn-primary" id="update">Update</button>
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Export <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="exportJSON">JSON</a></li>
                                <li><a href="#" id="exportCSV">CSV</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- /.form group -->
                <div class="form-group">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#chart-tab" aria-controls="chart-tab" role="tab" data-toggle="tab">Chart</a></li>
                        <li role="presentation"><a href="#table-tab" aria-controls="table-tab" role="tab" data-toggle="tab">Table</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="chart-tab">
                            <div class="chart-b">
                                <canvas id="lineChart" style="height:200px"></canvas>
                            </div>
                            <div id="legend">

                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="table-tab">
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
@endsection

@section('scripts')
    <script src="//maps.google.com/maps/api/js"></script>
    <script src="{{ asset('js/gmaps.js')  }}"></script>

    <script src="{{ asset('plugins/chartjs/Chart.min.js')  }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.Scatter.min.js')  }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var jsonData;

            var _current = {{ config('aero.default_marker') }};
            $("#range").val(moment().subtract(6, 'days').format('DD/MM/YYYY HH:mm:ss') + ' - ' + moment().format('DD/MM/YYYY HH:mm:ss'));
            $('#range').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'DD/MM/YYYY HH:mm:ss',
                ranges: {
                    'Today': [moment().startOf('day'), moment()],
                    'Yesterday': [moment().startOf('day').subtract(1, 'days'), moment().endOf('day').subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            });
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
                    _current = e.id;
                    updateData();
                }
            });
            @endforeach

            $("#update").click(function() {
               updateData();
            });

            var colors = [];
            @foreach(array_keys(config('aerometrics.properties')) as $property)
            colors['{{ $property }}'] = '{{ config('aerometrics.properties.' . $property . '.color') }}';
            @endforeach

            function updateData() {
                if(_current == -1) return;
                var properties = [];
                $(".minimal").each(function(i, j) {
                    var property = $(j);
                    if(property.prop('checked') == true) {
                        properties.push(property.data('property'));
                    }
                });
                var range = $("#range").val();
                $.ajax({
                    url: '/api/stations/' + _current,
                    type: 'POST',
                    dataType: 'json',
                    data: {'range': range, 'fields': properties},
                    success: function (aerometrics) {
                        jsonData = aerometrics;
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
                                if (aerometric.hasOwnProperty(property) && property != 'station_id' && property != 'created_at') {
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
                        properties.forEach(function(property) {
                            tableHtml += '<th>' + property + '</th>';
                        });
                        tableHtml += '</tr>';
                        dates.forEach(function(date) {
                            tableHtml += '<tr>';
                            tableHtml += '<th><small>' + date + '</small></th>';
                            properties.forEach(function(property) {
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
            $('input[type="checkbox"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue'
            });
            updateData();

            $("#exportJSON").click(function() {
                download(JSON.stringify(jsonData), 'json');
                return false;
            });
            $("#exportCSV").click(function() {
                download(JSONToCSVConvertor(jsonData), 'csv');
                return false;
            });
        });

        function JSONToCSVConvertor(JSONData) {
            var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
            var CSV = '';
            var row = "";

            for (var index in arrData[0]) {
                row += index + ',';
            }

            row = row.slice(0, -1);

            CSV += row + "\r\n";

            for (var i = 0; i < arrData.length; i++) {
                var row = "";

                for (var index in arrData[i]) {
                    row += '"' + arrData[i][index] + '",';
                }

                row = row.slice(0, -1);

                CSV += row + "\r\n";
            }

            if (CSV == '') {
                return;
            }

            CSV = CSV.slice(0, -2);

            return CSV;
        }

        function download(data, type) {
            var uri = 'data:text/' + type + ';charset=utf-8,' + escape(data);

            var link = document.createElement("a");
            link.href = uri;

            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = "" + Date.now() + "." + type;

            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection