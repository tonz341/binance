@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Orders History</div>
                <div class="card-body">
                    <table  class="table-responsive">
                        <thead class="thead-light">
                        <th width="25%">Order ID</th>
                        <th width="15%">Symbol</th>
                        <th width="25%">BTC-Price</th>
                        <th width="25%">Price</th>
                        <th width="10%">Status</th>
                        </thead>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }} <br> <small> {{ $order->created_at->format('Y-m-d h:i:s a') }} </small> </td>
                                <td>{{ $order->symbol }}</td>
                                <td>{{ $order->btc_price }}</td>
                                <td>({{ $order->side }}){{ $order->price }}</td>
                                <td>{{ $order->status }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
