@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">BTCUSD History</div>
                <div class="card-body">
                    <table  class="table-responsive">
                        <thead class="thead-light">
                        <th width="25%">Date</th>
                        <th width="25%">Price</th>
                        </thead>

                        @foreach($prices as $price)
                            <tr>
                                <td> {{ $price->created_at->format('Y-m-d h:i:s a') }}</td>
                                <td>{{ $price->price }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
