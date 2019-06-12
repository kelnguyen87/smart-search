@extends('layouts.master')
@section('content')
    <section class="content container-fluid">
        <div class="content-header header-fix">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <H2>Settings</H2>
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
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h3 class="mt-0">General setting</h3>
                    <span>Enter a generic message to display if multiple offers are triggered</span>
                </div>
            </div>
            <div class="col-md-12">
                <form action="/setting/save" method="post" id="config_form">
                    @csrf
                    <div class="box box-primary form-trigger-event">
                        <div class="box-body general-setting">
                            <div class="form-group">
                                <div class="data-box">
                                    <label for="offer_title">Offer Title</label>
                                    <input type="text" name="general_title" value="{{$data['general_title']}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="offer_title">Description</label>
                                <textarea name="general_description" rows="4" cols="50" class="form-control">{{$data['general_description']}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary form-trigger-event">
                        <div class="box-body general-setting">
                            <div class="form-group">
                                <div class="data-box">
                                    <label for="offer_title">Add To Cart Button Title</label>
                                    <input type="text" name="general_button_title" value="{{$data['general_button_title']}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="data-box">
                                    <label for="offer_title">Add To Cart Alert</label>
                                    <input type="text" name="added_alert_text" value="{{$data['added_alert_text']}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="data-box">
                                    <label for="offer_title">Continue Button Text</label>
                                    <input type="text" name="continue_shop_text" value="{{$data['continue_shop_text']}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="data-box">
                                    <label for="offer_title">Go To Cart Text</label>
                                    <input type="text" name="go_cart_text" value="{{$data['go_cart_text']}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('page-script')
    <script type="text/javascript">
        $('#save').click(function () {
            $('#config_form').submit();
        });
    </script>
@stop
