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
                        <th width="15%">Order ID</th>
                        <th width="15%">Symbol</th>
                        <th width="15%">Side</th>
                        <th width="15%">Price</th>
                        <th width="15%">Status</th>
                        <th width="15%">Created</th>
                        </thead>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }} <br></td>
                                <td>{{ $order->symbol }}</td>
                                <td>{{ $order->side }}</td>
                                <td>{{ $order->price }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at->format('Y-m-d h:i:s a') }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
