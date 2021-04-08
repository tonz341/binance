@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Orders History
                      <span class="float-right">
                        <small>
                            <strong>BTC:</strong>  {{ \Illuminate\Support\Facades\Cache::get('BTCUSDC') }} &nbsp; | &nbsp;
                            <strong>RSI-1h-14: </strong>  {{ \Illuminate\Support\Facades\Cache::get('RSI_1D_14') }}  &nbsp;
                        </small>
                     </span>
                </div>
                <div class="card-body">
                    <table  class="table-responsive">
                        <thead class="thead-light">
                        <th width="25%">Order ID</th>
                        <th width="15%">Symbol</th>
                        <th width="25%">Price</th>
                        <th width="25%">BTC-Price</th>
                        <th width="10%">Status</th>
                        <th width="10%">Type</th>
                        </thead>

                        @foreach($orders as $order)
                            <tr>
                                <td><strong>[{{ $order->side }}]</strong> - {{ $order->order_id }} <br> <small> {{ $order->created_at->format('Y-m-d h:i:s a') }} </small> </td>
                                <td>{{ $order->symbol }}</td>
                                <td>{{ $order->price }}</td>
                                <td>{{ $order->btc_price }}</td>
                                <td>{{ $order->status }}</td>
                                <td> {{ $order->type }} </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
