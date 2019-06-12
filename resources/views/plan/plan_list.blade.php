@extends('layouts.master')
@section('content')
    <section class="content container-fluid">
        <div class="content-header">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2>Price Plans</h2>
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
                                <th>#</th>
                                <th>Name</th>
                                <th>View limit</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            @foreach($plans as $plan)
                                <tr>
                                    <td>
                                        {{$plan->id}}
                                    </td>
                                    <td>
                                        {{$plan->name}}
                                    </td>
                                    <td>
                                        @if ($plan->capped_amount >0)
                                            {{(int)$plan -> capped_amount}}
                                        @else
                                            Unlimited
                                        @endif
                                    </td>
                                    <td>
                                        $ {{$plan->price}}
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
