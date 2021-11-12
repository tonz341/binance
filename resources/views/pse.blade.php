@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    Dashboard
                       <span class="float-right">
                           <a href="{{ $stocks['psei']['link'] }}" target="_blank">
                                {{ $stocks['psei']['name'] }}
                            </a>
                           <span class="{{ $stocks['psei']['add_class'] }}"> {{ $stocks['psei']['volume'] }} ({{ $stocks['psei']['change'] }}%)</span>
                        <small>
                                {{ $stocks['last_updated'] }} (<span id="reload_counter">0</span>)
                        </small>
                     </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Holds</strong></p>
                                <div class="row">
                                    @foreach($stocks['holds'] as $stock)
                                        <div class="col-md-3">
                                            <div class="card" style="margin-bottom: 20px">
                                                <div class="card-header">
                                                    <a href="{{ $stock['link'] }}" target="_blank">
                                                        <strong> {{ $stock['name'] }} <span class="float-right {{ $stock['add_class'] }}">({{ $stock['change'] }}%)</span></strong>
                                                    </a>
                                                </div>
                                                <div class="card-body">
                                                    <h3 class="{{ $stock['add_class'] }}">P {{ $stock['price'] }} </h3>
                                                    <small>{{ $stock['volume_format'] }} shares</small>
                                                    <br>
                                                    <small>{{ $stock['value_turn_over'] }} value</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p><strong>Observe</strong></p>
                                <div class="row">
                                    @foreach($stocks['observe'] as $stock)
                                        <div class="col-md-3">
                                            <div class="card" style="margin-bottom: 20px">
                                                <div class="card-header">
                                                    <a href="{{ $stock['link'] }}" target="_blank">
                                                        <strong> {{ $stock['name'] }} <span class="float-right {{ $stock['add_class'] }}">({{ $stock['change'] }}%)</span></strong>
                                                    </a>
                                                </div>
                                                <div class="card-body">
                                                    <h3 class="{{ $stock['add_class'] }}">P {{ $stock['price'] }}</h3>
                                                    <small>{{ $stock['volume_format'] }} shares</small>
                                                    <br>
                                                    <small>{{ $stock['value_turn_over'] }} value</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <p><strong>Gainers</strong></p>
                                        <div class="no-scroll">
                                            <table class="table">
                                                @foreach($stocks['gainers'] as $stock)


                                                    <tr>
                                                        <td class="no-padding">
                                                            <a href="{{ $stock['link'] }}" target="_blank">
                                                                {{ $stock['name'] }}
                                                            </a>
                                                        </td>
                                                        <td class="no-padding"><strong>{{ $stock['change'] }}%</strong></td>
                                                        <td class="no-padding" style="text-align: right">{{ $stock['volume_format'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <p><strong>Losers</strong></p>
                                        <div class="no-scroll">
                                            <table class="table">
                                                @foreach($stocks['losers'] as $stock)
                                                    <tr>
                                                        <td class="no-padding">
                                                            <a href="{{ $stock['link'] }}" target="_blank">
                                                                {{ $stock['name'] }}
                                                            </a>
                                                        </td>
                                                        <td class="no-padding"><strong>{{ $stock['change'] }}%</strong></td>
                                                        <td class="no-padding" style="text-align: right">{{ $stock['volume_format'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <p><strong>Active</strong></p>
                                        <div class="no-scroll">
                                            <table class="table">
                                                @foreach($stocks['volume'] as $stock)
                                                    <tr>
                                                        <td class="no-padding">
                                                            <a href="{{ $stock['link'] }}" target="_blank">
                                                                {{ $stock['name'] }}
                                                            </a>
                                                        </td>
                                                        <td class="no-padding"><strong>{{ $stock['change'] }}%</strong></td>
                                                        <td class="no-padding" style="text-align: right">{{ $stock['volume_format'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>

    var x = 0;
    var time = 30

    setTimeout(function(){
        location.reload();
    },time * 1000)

    setInterval(function(){
        document.getElementById("reload_counter").innerHTML  = (time - x);
        x++;
    },1000)

</script>
