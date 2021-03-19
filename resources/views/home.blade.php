@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Orders History</div>
                <div class="card-body">
                    <table  class="table">
                        <thead class="thead-light">
                        <th>Order ID</th>
                        <th>Symbol</th>
                        <th>Side</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th >Created at </th>
                        </thead>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }} <br></td>
                                <td>{{ $order->symbol }}</td>
                                <td>{{ $order->side }}</td>
                                <td>{{ $order->price }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
