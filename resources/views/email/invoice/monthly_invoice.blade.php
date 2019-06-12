Last Month Upsell App Pricing <br>
@if (isset($last_month['view']))
    Month: {{$last_month['month']}}/{{$last_month['year']}} <br>
    View count: {{$last_month['view']}} <br>
    Price: $ {{$last_month['price']}} on {{$last_month['name']}} <br>
@else
    Invoice not available
@endif
