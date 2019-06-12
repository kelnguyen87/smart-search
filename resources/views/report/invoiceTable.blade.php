{{--@php--}}
{{--var_dump($view_by_months);--}}
{{--@endphp--}}
@extends('layouts.master')
@section('content')
    <section class="content container-fluid">
        <div class="content-header">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2>View by months</h2>
                </div>
            </div>
        </div>
        <div class="box box-list">
            <div class="box-body">
                <div id="list-offer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-list-offers table-hover dataTable">
                            <thead>
                            <tr role="row">
                                <th>Month</th>
                                <th>Views</th>
                                <th>Plan</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tr>
                                <td>
                                    <b>{{sprintf("%02d", $view_this_month['month'])}} / {{$view_this_month['year']}} (Current month)</b>
                                </td>
                                <td>
                                    <b>{{(int)$view_this_month['view']}}</b>
                                </td>
                                <td>
                                    {{$view_this_month['name']}}
                                </td>
                                <td>
                                    <b>$ {{$view_this_month['price']}}</b>
                                </td>
                                <td>
                                    N/A
                                </td>
                            </tr>
                            @foreach($view_by_months as $value)
                                <tr>
                                    <td>
                                        @php
                                            $date = $value['activated_on'];
                                            $month = date("m",strtotime($date));
                                            $year = date("Y",strtotime($date));
                                        @endphp
                                        {{$month}} / {{$year}}
                                    </td>
                                    <td>
                                        <b>{{(int)$value['capped_amount']}}</b>
                                    </td>
                                    <td>
                                        {{$value['name']}}
                                    </td>
                                    <td>
                                        <b>$ {{$value['price']}}</b>
                                    </td>
                                    <td>
                                        @if ($value['status'] == 0)
                                            Unpaid
                                        @else
                                            Paid
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




