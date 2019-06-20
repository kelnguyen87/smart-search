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
                    <!--<div class="fillter-group ">
                        <form>
                            <label>Date and time range:</label>
                            <div id="reportrange">

                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </form>
                    </div>-->
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
                            <h3 class="box-title">Top 10 search queries with results</h3>
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

                                            {{round(($value->total  /$totalSearchQueries)*100,2 )}}%

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
                            <h3 class="box-title">Top 10 search with no results</h3>
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

                                            {{round(($value->total  /$totalSearchNoResult)*100,2)}}%

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

                        let pieOptions = {
                            // Boolean - Whether we should show a stroke on each segment
                            segmentShowStroke    : true,
                            // String - The colour of each segment stroke
                            segmentStrokeColor   : '#fff',
                            // Number - The width of each segment stroke
                            segmentStrokeWidth   : 1,
                            // Number - The percentage of the chart that we cut out of the middle
                            percentageInnerCutout: 50, // This is 0 for Pie charts
                            // Number - Amount of animation steps
                            animationSteps       : 100,
                            // String - Animation easing effect
                            animationEasing      : 'easeOutBounce',
                            // Boolean - Whether we animate the rotation of the Doughnut
                            animateRotate        : true,
                            // Boolean - Whether we animate scaling the Doughnut from the centre
                            animateScale         : false,
                            // Boolean - whether to make the chart responsive to window resizing
                            responsive           : true,
                            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                            maintainAspectRatio: false
                        };

                        $('#in-progress').hide();

                        var addedChartCanvas = $('#added-chart').get(0).getContext('2d');
                        var addedChart = new Chart(addedChartCanvas);
                        addedChart.Doughnut(amountChartData, pieOptions);
                        $.each(amountChartData, function (i, val) {
                            fillLegend(i, val,'#added-chart');
                        });
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
