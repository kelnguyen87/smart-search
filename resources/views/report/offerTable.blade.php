<table class="table table-bordered table-list-offers table-hover dataTable">
    <thead>
    <tr role="row">
        <th class="ls-name">Name</th>
        <th class="ls-type">Type</th>
        <th class="ls-percent-add">Views</th>
        <th class="ls-added-amount">Added to cart</th>
        <th class="ls-added">Added to cart %</th>
        <th class="ls-added-amount">Purchase</th>
        <th class="ls-added-amount">Purchase %</th>
        <th class="ls-added-amount">Purchase Amount</th>
    </tr>
    </thead>
    @foreach($offerList as $value)
        <tr>
            <td class="ls-name">
                <span> <a href="/offer/edit/{{$value->id}}">{{$value->name}}</a></span>
            </td>
            <td>
                {{$value->type}}
            </td>
            <td>
                {{$value->total}}
            </td>
            <td>
                @if(isset($append_data[$value->id]))
                    {{$append_data[$value->id]['added']}}
                @else
                    0
                @endif
            </td>
            <td>
                @if(isset($append_data[$value->id]) && $value->total != 0)
                    {{round(($append_data[$value->id]['added'] /$value->total)*100,2)}} %
                @else
                    0%
                @endif
            </td>
            <td>
                @if(isset($append_data[$value->id]) && isset($append_data[$value->id]['purchase']))
                    {{$append_data[$value->id]['purchase']}}
                @else
                    0
                @endif
            </td>
            <td>
                @if(isset($append_data[$value->id]) && isset($append_data[$value->id]['purchase']) && isset($append_data[$value->id]['added']))
                    {{round($append_data[$value->id]['purchase']/$append_data[$value->id]['added']*100,2)}} %
                @else
                    0
                @endif
            </td>
            <td>
                @if(isset($append_data[$value->id]) && isset($append_data[$value->id]['purchase']))
                    {{$append_data[$value->id]['amount']}}
                @else
                    0
                @endif
            </td>
        </tr>
    @endforeach
</table>
{!! $offerList->links() !!}
