@extends('layouts.master')
@section('content')
    <section class="content content--main">
        <div class="content-header ">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <H2> Search results widget</H2>
                </div>
                <div class="col-md-5 text-right">
                    <button class="btn btn-default" id="discard">Discard</button>
                    <button class="btn btn-primary" id="save">Save</button>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="/setting/save" method="post" id="config_form" class="form-horizontal">
                    @csrf
                    <div class="box box-list form-trigger-event">
                        <div class="box-body general-setting">
                            <div class="wrap-header2">
                                <h3>
                                    Products
                                </h3>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Show price</label>
                                <div class="col-sm-10">
                                    <div class="checkbox"> <label> <input type="checkbox" name="results_product_price" value="Y" checked="checked"> </label> </div>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Show compare at price</label>
                                <div class="col-sm-10">
                                    <div class="checkbox"> <label> <input type="checkbox" name="results_product_compare" value="Y" checked="checked"> </label> </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Show SKU</label>
                                <div class="col-sm-10">
                                    <div class="checkbox"> <label> <input type="checkbox" name="results_product_sku" value="Y" checked="checked"> </label> </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Products per page</label>
                                <div class="col-sm-10">
                                        <select name="results_product_page" id="ResultsItemCount" class=" form-control form-control-1x">
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

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Max title strings</label>
                                <div class="col-sm-10">
                                    <select name="results_product_page" id="ResultsItemCount" class=" form-control form-control-1x">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2" selected="selected">2</option>
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
                                        <option value="15" >15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>

                                    </select>
                                    <p class="help-block">The setting applies only to the grid view.</p>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Default view</label>
                                <div class="col-sm-10">
                                    <select name="results_product_gridview" class=" form-control form-control-1x">
                                        <option value="grid" selected="selected">Grid</option>
                                        <option value="list">List</option>
                                    </select>


                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Show button</label>
                                <div class="col-sm-10">

                                    <select name="results_product_button" class=" form-control form-control-2x">
                                        <option value="N">None</option>
                                        <option value="view_product">View product</option>
                                        <option value="add_to_cart" selected="selected">Add to cart</option>
                                        <option value="quick_view">Quick view (PRO)</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Zero price action</label>
                                <div class="col-sm-10">

                                    <select name="results_product_price" class=" form-control form-control-2x">
                                        <option value="show_zero_price" selected="selected">Show zero price</option>
                                        <option value="hide_zero_price">Hide zero price</option>
                                        <option value="show_custom_text">Hide zero price, show text instead</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Default sorting</label>
                                <div class="col-sm-10">

                                    <select name="results_product_sorting" id="ResultsItemCount" class=" form-control form-control-2x">
                                        <option value="relevance:desc" selected="selected">Relevance</option>
                                        <option value="title:asc">Title: A-Z</option>
                                        <option value="title:desc">Title: Z-A</option>
                                        <option value="created:desc">Date: New to Old</option>
                                        <option value="created:asc">Date: Old to New</option>
                                        <option value="price:asc">Price: Low to High</option>
                                        <option value="price:desc">Price: High to Low</option>
                                        <option value="sales_amount:desc">Bestselling</option>

                                    </select>
                                    <p class="help-block">Please note that if the default sorting differs from "Relevance" it may result in showing results of low relevance on the 1st search results page. The reason is search results will no longer be sorted by relevance.</p>
                                </div>

                            </div>
                            <div class="wrap-header2">
                                <h3>
                                    Categories
                                </h3>
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
