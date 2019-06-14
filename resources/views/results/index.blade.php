@extends('layouts.master')
@section('content')
<section class="content content--main">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    @endif
    <div class="content-header ">
        <div class="row align-items-center">
            <div class="col-md-7">
                <H2> Search Setting</H2>
            </div>
            <div class="col-md-5 text-right">
              <!--  <button class="btn btn-default" id="discard">Discard</button>-->
                <button class="btn btn-primary" id="save">Save</button>
            </div>
        </div>
    </div>


    <form action="/setting/save" method="post" id="config_form" class="form-horizontal">
        @csrf
        <div class="box box-list form-trigger-event">
            <div class="box-body general-setting">
                <div class="wrap-header2">
                    <h3>Products</h3>
                </div>
                @php
                    $perPage = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'];
                    $priceAction = ['show_zero_price' => 'Show zero price','hide_zero_price' => 'Hide zero price','show_custom_text' => 'Hide zero price, show text instead'];
                    $sorting = ['relevance:desc' => 'Relevance','title:asc' => 'Title: A-Z','title:desc' => 'Title: Z-A','created:desc' => 'Date: New to Old','created:asc' => 'Date: Old to New','sales_amount:desc' => 'Bestselling'];
                @endphp

                <div class="form-group">
                    {!! Form::label('showprice', 'Show price', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_product_price','yes', $data['general_product_price']) !!}</label></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('showcompare', 'Show compare at price', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_product_compare','yes', $data['general_product_compare']) !!}</label></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('showsku', 'Show SKU', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_product_sku','yes', $data['general_product_sku']) !!}</label></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('perpage', 'Products per page', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_per', $perPage,  $data['general_product_per'], ['class' => 'form-control form-control-1x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('Maxtitle', 'Max title strings', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_title', $perPage,  $data['general_product_title'], ['class' => 'form-control form-control-1x' ]) !!}
                        <p class="help-block">The setting applies only to the grid view.</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('Maxtitle', 'Default view', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_gridview', ['grid' => 'Grid', 'list' => 'List'],  $data['general_product_gridview'], ['class' => 'form-control form-control-1x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('forsample', 'Show button', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_button', ['none' => 'None', 'view_product' => 'View product','add_to_cart' => 'Add to cart'],  $data['general_product_button'], ['class' => 'form-control form-control-2x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('forsample', 'Zero price action', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_zero_price', $priceAction,  $data['general_product_zero_price'], ['class' => 'form-control form-control-2x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('forsample', 'Default sorting', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_sorting', $sorting,  $data['general_product_sorting'], ['class' => 'form-control form-control-2x' ]) !!}
                        <p class="help-block">Please note that if the default sorting differs from "Relevance" it may result in showing results of low relevance on the 1st search results page. The reason is search results will no longer be sorted by relevance.</p>
                    </div>
                </div>


                <div class="wrap-header2">
                    <h3>Categories</h3>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Show images</label>
                    <div class="col-sm-10">
                        <div class="checkbox"> <label> <input type="checkbox" name="results_categories_price" value="Y" checked="checked"> </label> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Categories per page</label>
                    <div class="col-sm-10">
                        <select name="results_categories_page" id="ResultsItemCount" class=" form-control form-control-1x">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15" selected="selected">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>

                        </select>
                    </div>
                </div>

            </div>
        </div>

    </form>

</section>
@endsection

@section('page-script')
    <script type="text/javascript">
    $('#save').click(function () {
    $('#config_form').submit();
    });
    </script>
@stop
