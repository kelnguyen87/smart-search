@extends('layouts.master')
@section('content')
    <!-- Main content -->
    <section class="view-dashboard content content--main">
        <div class="content-header">

            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2>Overview dashboard</h2>
                </div>
                <div class="col-sm-6 col-xs-12 text-right">
                    <div class="fillter-group mb-20 ">
                        <form>
                            <!--<label>Date and time range:</label>-->
                            <div id="reportrange">

                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="cavas_report--main">
                    <div class="cavas_report">
                        <canvas id="added-chart" style="height: 265px; width: 530px;" width="530" height="265"></canvas>
                        <div>
                            <ul id="added-chart-legend"></ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-list">
                    <div class="offer-by-view-box box-body">
                        <div class="box-header">
                            <h3 class="box-title">Top search queries</h3>
                        </div>

                        <table id="snize_table_top_search_queries" cellpadding="0" cellspacing="0" border="0" class="table table-striped snize-view snize-table-with-shadow">
                            <thead>
                            <tr class="snize-headings">
                                <th>Phrase</th>
                                <th>Search count</th>
                                <th>% of all searches</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if( $dataSearchQueries )
                                @php  $totalSearchQueries = 0; @endphp
                                @foreach($dataSearchQueries as $value)
                                    @php $totalSearchQueries +=  $value->total @endphp
                                @endforeach
                                @foreach($dataSearchQueries as $value)
                                    <tr>
                                        <td>
                                            {{$value->phrase}}
                                        </td>
                                        <td class="ls-name">
                                            {{$value->total}}
                                        </td>
                                        <td class="ls-type">

                                            {{round(($value->total/$totalSearchQueries)*100 )}}%

                                        </td>

                                    </tr>
                                @endforeach

                            @else
                                <tr>
                                    <td colspan="3" class="center">No phrases</td>
                                </tr>
                            @endif


                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-list">
                    <div class="offer-by-conversion-box box-body">
                        <div class="box-header">
                            <h3 class="box-title">Top search with no results</h3>
                        </div>
                        <table id="snize_table_top_no_results_search_queries" cellpadding="0" cellspacing="0" border="0" class="table table-striped snize-view snize-table-with-shadow">
                            <thead>
                            <tr class="snize-headings">
                                <th>Phrase</th>
                                <th>Search count</th>
                                <th>% of all searches</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if( !$dataSearchNoResult->isEmpty() )

                                @php  $totalSearchNoResult = 0; @endphp
                                @foreach($dataSearchNoResult as $value)
                                    @php $totalSearchNoResult +=  $value->total @endphp
                                @endforeach
                                @foreach($dataSearchNoResult as $value)
                                    <tr>
                                        <td>
                                            {{$value->phrase}}
                                        </td>
                                        <td class="ls-name">
                                            {{$value->total}}
                                        </td>
                                        <td class="ls-type">

                                            {{round(($value->total/$totalSearchNoResult)*100 )}}%

                                        </td>

                                    </tr>
                                @endforeach

                            @else
                                <tr>
                                    <td colspan="3" class="center">No phrases</td>
                                </tr>
                            @endif


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('page-script')
    <script type="text/javascript">
        $(function () {
            var start = moment().subtract(7, 'days');

            var end = moment();

            function fillViewTable(data) {
                let lowest = data.lowestView;
                let highest = data.highestView;
                $('#highest-view-list').html('');
                $.each(highest,function (i,val) {
                    $('#highest-view-list').append($('<tr>')
                        .append($('<td>',{text:val.offer_name}))
                        .append($('<td>',{text:val.offer_view+' Views'}))
                        .append($('<td>').append($('<a>',{text:' Edit', href:'/offer/edit/'+val.id}))));
                });
                $('#lowest-view-list').html('');
                $.each(lowest,function (i,val) {
                    $('#lowest-view-list').append($('<tr>')
                        .append($('<td>',{text:val.offer_name}))
                        .append($('<td>',{text:val.offer_view+' Views'}))
                        .append($('<td>').append($('<a>',{text:' Edit', href:'/offer/edit/'+val.id}))));
                });
            }

            function fillConversionTable(data) {
                let lowest = data.lowestConversion;
                let highest = data.highestConversion;
                $('#highest-conversion-list').html('');
                $.each(highest,function (i,val) {
                    $('#highest-conversion-list').append($('<tr>')
                        .append($('<td>',{text:val.offer_name}))
                        .append($('<td>',{text:val.amount + '(' +val.conversion+' %)'})));
                });
                $('#lowest-conversion-list').html('');
                $.each(lowest,function (i,val) {
                    $('#lowest-conversion-list').append($('<tr>')
                        .append($('<td>',{text:val.offer_name}))
                        .append($('<td>',{text:val.amount + '(' +val.conversion+' %)'})));
                });
            }

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                fillter(start.format('DD-MM-YYYY'),end.format('DD-MM-YYYY'));
            }
            function fillLegend(i,val,id) {
                $(id+'-legend').append($('<li>',{text: val.label}).append($('<i>',{class: 'fa fa-circle-o', style:'color:'+val.color})))
            }

            function fillter(start,end) {
                $('#in-progress').show();


                $.ajax({
                    url: 'report/chartData',
                    type: 'GET',
                    data: {
                        start: start,
                        end: end
                    },
                    dataType: 'json',
                    success: function (response) {

                        amountChartData = response.product_amount;
                        var dataLabels = [];
                        var datavalue = [];

                        $.each(amountChartData, function() {
                            dataLabels.push (this.label);
                            datavalue.push (this.value)
                        });


                        var areaChartData = {
                            labels  : dataLabels,
                            datasets: [

                                {
                                    label               : 'Digital Goods',
                                    fillColor           : 'rgba(60,141,188,0.9)',
                                    strokeColor         : 'rgba(60,141,188,0.8)',
                                    pointColor          : '#3b8bba',
                                    pointStrokeColor    : 'rgba(60,141,188,1)',
                                    pointHighlightFill  : '#fff',
                                    pointHighlightStroke: 'rgba(60,141,188,1)',
                                    data                : datavalue
                                }
                            ]
                        }




                var pieOptions = {
                    //Boolean - If we should show the scale at all
                    showScale               : true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines      : true,
                    //String - Colour of the grid lines
                    scaleGridLineColor      : 'rgba(0,0,0,.05)',
                    //Number - Width of the grid lines
                    scaleGridLineWidth      : 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines  : true,
                    //Boolean - Whether the line is curved between points
                    bezierCurve             : true,
                    //Number - Tension of the bezier curve between points
                    bezierCurveTension      : 0.3,
                    //Boolean - Whether to show a dot for each point
                    pointDot                : true,
                    //Number - Radius of each point dot in pixels
                    pointDotRadius          : 4,
                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth     : 1,
                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius : 20,
                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke           : true,
                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth      : 2,
                    //Boolean - Whether to fill the dataset with a color
                    datasetFill             : true,
                    //String - A legend template


                            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                            maintainAspectRatio     : true,
                            //Boolean - whether to make the chart responsive to window resizing
                            responsive              : true
                        }

                        $('#in-progress').hide();
                        //addedChartData = response.product_view;


                        var addedChartCanvas = $('#added-chart').get(0).getContext('2d');
                        var addedChart = new Chart(addedChartCanvas);
                        addedChart.Line(areaChartData, pieOptions);
                        /*$.each(amountChartData, function (i, val) {
                            fillLegend(i, val,'#added-chart');
                        });*/
                    }

                });
            }
            $('#reportrange').daterangepicker({
                //startDate: start,
                //endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],

                }

            }, cb);
            cb(start, end);
        });
    </script>
@stop
