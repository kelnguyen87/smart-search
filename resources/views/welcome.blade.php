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
                <div class="snize-graph-legend-item snize-total-searches cm-legend snize-current" id="legend_total_searches_chart">
                    <div class="snize-graph-legend-item-label">Total searches</div>
                    <div class="snize-graph-legend-item-value">0</div>
                </div>
                <div id="content_chart" style="clear: both;"><div id="total_searches_chart" class="snize-analytics-chart" style="height: 250px"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 1359px; height: 250px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="1359" height="250" aria-label="A chart." style="overflow: hidden;"><defs id="defs"><clipPath id="_ABSTRACT_RENDERER_ID_53"><rect x="30" y="20" width="1291" height="200"></rect></clipPath></defs><rect x="0" y="0" width="1359" height="250" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="30" y="20" width="1291" height="200" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(https://shopify-app.searchserverapi.com/admin#_ABSTRACT_RENDERER_ID_53)"><g><rect x="113" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="202" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="291" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="380" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="469" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="558" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="647" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="736" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="825" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="914" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="1003" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="1092" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="1181" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="1270" y="20" width="1" height="200" stroke="none" stroke-width="0" fill="#f0f0f0"></rect><rect x="30" y="20" width="1291" height="1" stroke="none" stroke-width="0" fill="#f0f0f0"></rect></g><g><rect x="30" y="219" width="1291" height="1" stroke="none" stroke-width="0" fill="#f0f0f0"></rect></g><g><path d="M30.5,219.5L74.98275862068965,219.5L119.4655172413793,219.5L163.94827586206895,219.5L208.4310344827586,219.5L252.91379310344826,219.5L297.3965517241379,219.5L341.87931034482756,219.5L386.3620689655172,219.5L430.84482758620686,219.5L475.3275862068965,219.5L519.8103448275862,219.5L564.2931034482758,219.5L608.7758620689655,219.5L653.2586206896551,219.5L697.7413793103448,219.5L742.2241379310344,219.5L786.7068965517241,219.5L831.1896551724137,219.5L875.6724137931034,219.5L920.155172413793,219.5L964.6379310344827,219.5L1009.1206896551723,219.5L1053.6034482758619,219.5L1098.0862068965516,219.5L1142.5689655172414,219.5L1187.051724137931,219.5L1231.5344827586207,219.5L1276.0172413793102,219.5L1320.5,219.5" stroke="#00bcd4" stroke-width="1.5" fill-opacity="1" fill="none"></path></g></g><g><circle cx="30.5" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="74.98275862068965" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="119.4655172413793" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="163.94827586206895" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="208.4310344827586" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="252.91379310344826" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="297.3965517241379" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="341.87931034482756" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="386.36206896551
72" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="430.84482758620686" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="475.3275862068965" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="519.8103448275862" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="564.2931034482758" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="608.7758620689655" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="653.2586206896551" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="697.7413793103448" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="742.2241379310344" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="786.7068965517241" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="831.1896551724137" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="875.6724137931034" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="920.155172413793" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="964.6379310344827" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1009.1206896551723" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1053.6034482758619" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1098.0862068965516" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1142.5689655172414" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1187.051724137931" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1231.5344827586207" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1276.0172413793102" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle><circle cx="1320.5" cy="219.5" r="4.5" stroke="none" stroke-width="0" fill="#00bcd4"></circle></g><g><g><text text-anchor="middle" x="113.9051724137931" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 3</text></g><g><text text-anchor="middle" x="202.8706896551724" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 5</text></g><g><text text-anchor="middle" x="291.8362068965517" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 7</text></g><g><text text-anchor="middle" x="380.801724137931" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 9</text></g><g><text text-anchor="middle" x="469.7672413793103" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 11</text></g><g><text text-anchor="middle" x="558.7327586206897" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 13</text></g><g><text text-anchor="middle" x="647.698275862069" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 15</text></g><g><text text-anchor="middle" x="736.6637931034483" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 17</text></g><g><text text-anchor="middle" x="825.6293103448276" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 19</text></g><g><text text-anchor="middle" x="914.5948275862069" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 21</text></g><g><text text-anchor="middle" x="1003.5603448275862" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 23</text></g><g><text text-anchor="middle" x="1092.5258620
689654" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 25</text></g><g><text text-anchor="middle" x="1181.4913793103447" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 27</text></g><g><text text-anchor="middle" x="1270.456896551724" y="236.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">Jun 29</text></g><g><text text-anchor="end" x="19" y="24.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#9f9f9f">1</text></g></g></g><g></g></svg></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 260px; left: 1369px; white-space: nowrap; font-family: Arial; font-size: 11px;">0</div><div></div></div></div> </div>
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
                                    @php $totalSearchQueries +=  $value->count @endphp
                                @endforeach
                                @foreach($dataSearchQueries as $value)
                                    <tr>
                                        <td>
                                            {{$value->phrase}}
                                        </td>
                                        <td class="ls-name">
                                            {{$value->count}}
                                        </td>
                                        <td class="ls-type">

                                            {{round(($value->count/$totalSearchQueries)*100 )}}%

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
                                    @php $totalSearchNoResult +=  $value->count @endphp
                                @endforeach
                                @foreach($dataSearchNoResult as $value)
                                    <tr>
                                        <td>
                                            {{$value->phrase}}
                                        </td>
                                        <td class="ls-name">
                                            {{$value->count}}
                                        </td>
                                        <td class="ls-type">

                                            {{round(($value->count/$totalSearchNoResult)*100 )}}%

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
                //fillter(start.format('DD-MM-YYYY'),end.format('DD-MM-YYYY'));
            }
            function fillter(start,end) {
                $('#in-progress').show();
                $.ajax({
                    url: 'report/getGeneral',
                    type: 'GET',
                    data: {
                        start: start,
                        end: end
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#total-view').text(response.view);
                        $('#total-added-cart').text(response.added_cart);
                        $('#total-conversion').text(response.conversion);
                        $('#total-revenue').text(response.revenue);
                        fillViewTable(response);
                        fillConversionTable(response);
                        $('#in-progress').hide();
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
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(start, end);
        });
    </script>
@stop
