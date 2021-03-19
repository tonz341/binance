@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Schedule</div>

                <div class="card-body">

                    <form action="/schedule/set" method="post">

                         {{ csrf_field()  }}

                        <div class="row">
                            <div class="col-sm">
                                <label for="symbol"> Symbol </label> <br>
                                <select name="symbol" id="symbol " required>
                                    <option value="XRPUSDC"> XRP / USDC </option>
                                    <option value="BTCUSDC"> BTC / USDC </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="side "> Side </label> <br>
                                <select name="side" id="side" required>
                                    <option value="buy"> Buy </option>
                                    <option value="sell"> Sell </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="amount "> Amount </label> <br>
                                <input type="number" value="" name="amount" required>
                            </div>

                            <div class="col-sm">
                                <label for="sequence "> Sequence </label> <br>
                                <select name="sequence" id="sequence" required>
                                    <option value="daily"> Daily </option>
                                    <option value="weekdays"> Weekdays </option>
                                    <option value="weekends"> Weekends </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="time "> Time </label> <br>
                                <select name="time" id="time">
                                    <option value="0"> 12 am </option>
                                    <option value="1"> 1 am </option>
                                    <option value="2"> 2 am </option>
                                    <option value="3"> 3 am </option>
                                    <option value="4"> 4 am </option>
                                    <option value="5"> 5 am </option>
                                    <option value="6"> 6 am </option>
                                    <option value="7"> 7 am </option>
                                    <option value="8"> 8 am </option>
                                    <option value="9"> 9 am </option>
                                    <option value="10"> 10 am </option>
                                    <option value="11"> 11 am </option>
                                    <option value="12"> 12 pm </option>
                                    <option value="13"> 1 pm </option>
                                    <option value="14"> 2 pm </option>
                                    <option value="15"> 3 pm </option>
                                    <option value="16"> 4 pm </option>
                                    <option value="17"> 5 pm </option>
                                    <option value="18"> 6 pm </option>
                                    <option value="19"> 7 pm </option>
                                    <option value="20"> 8 pm </option>
                                    <option value="21"> 9 pm </option>
                                    <option value="22"> 10 pm </option>
                                    <option value="23"> 11 pm </option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label for="minutes "> Min </label> <br>
                                <select name="minutes" id="minutes">
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{ $i }}"> {{ $i  }} m </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-sm">
                                <br>
                                <button type="submit" class="btn btn-primary"> Set </button>
                            </div>
                        </div>
                    </form>

                    <hr >

                    <div class="row">

                        <table width="100%" border="1">
                            <thead>
                                <th>Symbol</th>
                                <th>Side</th>
                                <th>Amount</th>
                                <th>Sequence</th>
                                <th >Time </th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>

                            @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->symbol }}</td>
                                <td>{{ $schedule->side }}</td>
                                <td>{{ $schedule->amount }}</td>
                                <td>{{ $schedule->sequence }}</td>
                                <td>{{ $schedule->time }} : {{ $schedule->minutes }}</td>
                                <td>{{ $schedule->status }}</td>
                                <td>

                                    @if($schedule->status === 0)
                                        <form action="/schedule/activate" method="post">
                                            {{ csrf_field()  }}
                                            <input type="hidden" name="id" value="{{ $schedule->id }}">
                                            <button type="submit" class="btn btn-success btn-sm"> Activate </button>
                                        </form>
                                    @else
                                        <form action="/schedule/deactivate" method="post">
                                            {{ csrf_field()  }}
                                            <input type="hidden" name="id" value="{{ $schedule->id }}">
                                            <button type="submit" class="btn btn-info btn-sm"> Deactivate </button>
                                        </form>
                                    @endif

                                    <form action="/schedule/delete" method="post" style="margin-top: 5px">
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
