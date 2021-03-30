@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>[AUTO-RSI]</strong> Create

                    <span class="float-right">
                        <small>
                          <strong>BTC:</strong>  {{ $rsi->price }} &nbsp; | &nbsp;
                          <strong>RSI-1h-14: </strong>  {{ $rsi->rsi_14_1d }}  &nbsp; | &nbsp;
                          <strong>Last: </strong>  {{ $rsi->created_at }}
                        </small>
                    </span>

                </div>
                <div class="card-body">
                    <form action="/schedule-rsi/set" method="post">
                         {{ csrf_field()  }}
                        <div class="row">
                            <div class="col-sm">
                                <label for="symbol"> Symbol </label> <br>
                                <select class="form-control" name="symbol" id="symbol " required>
                                    <option value="BTCUSDC"> BTC / USDC </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="side "> Side </label> <br>
                                <select class="form-control" name="side" id="side" required>
                                    <option value="buy"> Buy </option>
                                    {{--<option value="buy"> Sell </option>--}}
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="auto_cycle "> Auto-Cycle </label> <br>
                                <select class="form-control" name="auto_cycle" id="auto_cycle">
                                    <option value="0" selected> No </option>
                                    <option value="1"> Yes </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="amount "> Amount </label> <br>
                                <input class="form-control" type="number" value="" name="amount" required>
                            </div>

                            <div class="col-sm">
                                <label for="rsi_interval "> RSI-Interval </label> <br>
                                <select class="form-control" name="rsi_interval" id="rsi_interval">
                                    <option value="1h"> 1hr </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="window_hour "> RSI-Period </label> <br>
                                <select class="form-control" name="rsi_period" id="rsi_period">
                                    <option value="14"> 14 </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="rsi "> RSI-Trigger </label> <br>
                                <select class="form-control" name="rsi" id="rsi">
                                    @for ($i = 5 ; $i < 100; $i = $i + 5)
                                        <option value="{{ $i }}"> < {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-sm" style="border-left: 1px solid black;">
                                <label for="target_sell "> Sell-on </label> <br>
                                <select class="form-control" name="target_sell" id="target_sell">
                                    @for ($i = 5 ; $i < 100; $i = $i + 5)
                                        <option value="{{ $i }}"> {{ $i }}%</option>
                                    @endfor
                                </select>
                            </div>


                            <div class="col-sm">
                                <label for="submit"> &nbsp; </label> <br>
                                <button type="submit" class="btn btn-primary"> Set </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-header"><strong>[AUTO-RSI]</strong> Schedules </div>
                <div class="card-body">
                    <div class="row">
                        <table  class="table">
                            <thead class="thead-light">
                            <th> Symbol </th>
                            <th> Side </th>
                            <th> Cycle </th>
                            <th> Amount </th>
                            <th> Period </th>
                            <th> Interval </th>
                            <th> RSI </th>
                            <th> Avg-Price </th>
                            <th> Sell-Trigger </th>
                            <th>Status</th>
                            <th>Action</th>
                            </thead>

                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->symbol }} <br> <small>{{ $schedule->next_schedule_at }}</small>
                                        <br>
                                        <small>Last note: {{ $schedule->notes }}</small>
                                    </td>
                                    <td>{{ $schedule->side }}</td>
                                    <td>{{ $schedule->auto_cycle ? 'Yes' : 'No' }}</td>
                                    <td>{{ $schedule->amount }}</td>
                                    <td>{{ $schedule->rsi_period }} </td>
                                    <td>{{ $schedule->rsi_interval }} </td>
                                    <td>{{ $schedule->rsi }} </td>
                                    <td>{{ $schedule->average_price }}</td>
                                    <td>{{ $schedule->target_sell }}%</td>
                                    <td>
                                        @if($schedule->status)
                                            Active
                                        @else
                                            Stop
                                        @endif
                                    </td>
                                    <td>
                                        @if($schedule->auto_cycle)

                                            @if($schedule->status === 0)
                                                <form action="/schedule-rsi/activate" method="post">
                                                    {{ csrf_field()  }}
                                                    <input type="hidden" name="id" value="{{ $schedule->id }}">
                                                    <button type="submit" class="btn btn-success btn-sm"> Activate </button>
                                                </form>
                                            @else
                                                <form action="/schedule-rsi/deactivate" method="post">
                                                    {{ csrf_field()  }}
                                                    <input type="hidden" name="id" value="{{ $schedule->id }}">
                                                    <button type="submit" class="btn btn-info btn-sm"> Deactivate </button>
                                                </form>
                                            @endif
                                        @endif

                                        <form action="/schedule-rsi/delete" method="post" style="margin-top: 5px">
                                            {{ csrf_field()  }}
                                            <input type="hidden" name="id" value="{{ $schedule->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm"> Remove </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection