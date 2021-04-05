@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>[BTD]</strong> Create
                     <span class="float-right">
                        <small>
                            <strong>BTC:</strong>  {{ \Illuminate\Support\Facades\Cache::get('BTCUSDC') }} &nbsp; | &nbsp;
                            <strong>RSI-1h-14: </strong>  {{ \Illuminate\Support\Facades\Cache::get('RSI_1D_14') }}  &nbsp;
                        </small>
                     </span>
                </div>
                <div class="card-body">
                    <form action="/schedule-btd/set" method="post">
                         {{ csrf_field()  }}
                        <div class="row">
                            <div class="col-sm">
                                <label for="symbol"> Symbol </label> <br>
                                <select class="form-control" name="symbol" id="symbol " required>
                                    <option value="BTCUSDC"> BTC / USDC </option>
                                    {{--<option value="XRPBTC"> XRP / BTC </option>--}}
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="side "> Side </label> <br>
                                <select class="form-control" name="side" id="side" required>
                                    <option value="buy"> Buy </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="amount "> Amount </label> <br>
                                <input class="form-control" type="number" value="" name="amount" required>
                            </div>

                            <div class="col-sm">
                                <label for="sequence "> Repeat </label> <br>
                                <select class="form-control" name="sequence" id="sequence" required>
                                    <option value="30_mins"> 30 mins </option>
                                    <option value="hourly" selected> 1 hour </option>
                                    <option value="daily" > 1 Day </option>
                                    <option value="weekly" > 1 Week </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="window_hour "> Window-Frame </label> <br>
                                <select class="form-control" name="window_hour" id="window_hour">
                                    @for ($i = 1 ; $i < 25; $i++)
                                        <option value="{{ $i }}"> {{ $i }} hr ago</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="trigger_percentage "> Percentage-Trigger </label> <br>
                                <select class="form-control" name="trigger_percentage" id="trigger_percentage">
                                    @for ($i = 5 ; $i < 100; $i = $i + 5)
                                        <option value="{{ 0 - $i }}"> {{ 0 - $i  }}%</option>
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
                <div class="card-header"><strong>[BTD]</strong> Schedules</div>
                <div class="card-body">
                    <div class="row">
                        <table  class="table">
                            <thead class="thead-light">
                            <th>Symbol</th>
                            <th>Side</th>
                            <th>Amount</th>
                            <th>Window </th>
                            <th>Trigger </th>
                            <th>Repeat</th>
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
                                    <td>{{ $schedule->amount }}</td>
                                    <td>{{ $schedule->window_hour }}hr ago</td>
                                    <td>{{ $schedule->trigger_percentage }}%</td>
                                    <td>{{ str_replace('_',' ',$schedule->sequence) }}</td>
                                    <td>
                                        @if($schedule->status)
                                            Active
                                        @else
                                            Stop
                                        @endif
                                    </td>
                                    <td>

                                        @if($schedule->status === 0)
                                            <form action="/schedule-btd/activate" method="post">
                                                {{ csrf_field()  }}
                                                <input type="hidden" name="id" value="{{ $schedule->id }}">
                                                <button type="submit" class="btn btn-success btn-sm"> Activate </button>
                                            </form>
                                        @else
                                            <form action="/schedule-btd/deactivate" method="post">
                                                {{ csrf_field()  }}
                                                <input type="hidden" name="id" value="{{ $schedule->id }}">
                                                <button type="submit" class="btn btn-info btn-sm"> Deactivate </button>
                                            </form>
                                        @endif

                                        <form action="/schedule-btd/delete" method="post" style="margin-top: 5px">
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