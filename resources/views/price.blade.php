@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">BTCUSD History
                       <span class="float-right">
                        <small>
                            <strong>BTC:</strong>  {{ \Illuminate\Support\Facades\Cache::get('BTCUSDC') }} &nbsp; | &nbsp;
                            <strong>RSI-1h-14: </strong>  {{ \Illuminate\Support\Facades\Cache::get('RSI_1D_14') }}  &nbsp;
                        </small>
                     </span>
                </div>
                <div class="card-body">
                    <table  class="table">
                        <thead class="thead-light">
                            <th width="25%">Date</th>
                            <th width="25%">Time</th>
                            <th width="25%">Price</th>
                            <th width="25%">RSI-14-1hr </th>
                        </thead>
                        @foreach($prices as $price)
                            <tr>
                                <td> {{ $price->created_at->format('Y-m-d') }}</td>
                                <td> {{ $price->created_at->format('h:i:s a') }}</td>
                                <td>{{ $price->price }}</td>
                                <td>{{ $price->rsi_14_1d }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
