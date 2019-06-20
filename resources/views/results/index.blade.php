@extends('layouts.master')
@section('content')
<section class="content content--main">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert">x</button>
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
                    <h3>Search Suggestion</h3>
                </div>
                <div class="form-group">
                    {!! Form::label('showprice', 'Show price', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_suggestion_price','yes', $data['general_suggestion_price']) !!}</label></div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('showdesc', 'Show Description', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_suggestion_derc','yes', $data['general_suggestion_derc']) !!}</label></div>
                    </div>
                </div>
                <div class="wrap-header2">
                    <h3>Search Result</h3>
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
                    {!! Form::label('showsku', 'Show reviews', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_product_reviews','yes', $data['general_product_reviews']) !!}</label></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('showsku', 'Show Description', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-sm-10">
                        <div class="checkbox"><label>{!! Form::checkbox('general_product_derc','yes', $data['general_product_derc']) !!}</label></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('Maxtitle', 'Max title strings', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_title', $perPage,  $data['general_product_title'], ['class' => 'form-control form-control-2x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('forsample', 'Show button', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_button', ['none' => 'None', 'view_product' => 'View product','add_to_cart' => 'Add to cart'],  $data['general_product_button'], ['class' => 'form-control form-control-2x' ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('perpage', 'Products per row', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_per', ['2' => '2 ', '3' => '3 ','4' => '4 ','5' => '5 '],  $data['general_product_per'], ['class' => 'form-control form-control-2x' ]) !!}
                    </div>
                </div>



                <div class="form-group">
                    {!! Form::label('forsample', 'Default sorting', ['class' => 'col-sm-2 control-label'] )  !!}
                    <div class="col-lg-10">
                        {!!  Form::select('general_product_sorting', $sorting,  $data['general_product_sorting'], ['class' => 'form-control form-control-2x' ]) !!}
                        <p class="help-block">Please note that if the default sorting differs from "Relevance" it may result in showing results of low relevance on the 1st search results page. The reason is search results will no longer be sorted by relevance.</p>
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
