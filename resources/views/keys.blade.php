@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Keys</div>

                <div class="card-body">

                    <form action="/keys/set" method="post">

                    {{ csrf_field()  }}

                        <div class="row">
                            <div class="col-sm">
                                <label for="api_key "> API Key </label> <br>
                                <input type="text" name="api_key" value="{{ $api_key }}" style="width: 100%">
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm">
                                <label for="api_secret "> Secret </label> <br>
                                <input width="100%" type="text" name="api_secret" value="{{ $api_secret }}"  style="width: 100%">
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm">
                                <br>
                                <button type="submit" class="btn btn-primary"> Set </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
