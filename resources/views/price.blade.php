@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">BTCUSD History</div>
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
