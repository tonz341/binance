@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Keys</div>

                <div class="card-body">

                    <p>Current API key: {{ $api_key }}</p>
                    <p>Current Secret: {{ $api_secret }}</p>

                    <hr>

                    <form action="/keys/set" method="post">

                    {{ csrf_field()  }}

                        <div class="row">
                            <div class="col-sm">
                                <label for="api_key "> API Key </label> <br>
                                <input class="form-control" type="text" name="api_key" required>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm">
                                <label for="api_secret "> Secret </label> <br>
                                <input class="form-control" width="100%" type="text" name="api_secret" required >
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm">
                                <br>
                                <button type="submit" class="btn btn-primary"> Set </button>
                            </div>
                        </div>
                    </form>

                    <br>

                    How to get this?
                    <ol>
                        <li>Login from your binance.com account</li>
                        <li>Go to API management, or click <a target="_blank" href="https://www.binance.com/en/my/settings/api-management"> here </a> </li>
                        <li>Create your API Label</li>
                        <li>Copy the API key and Secret key here. (Note: Secret key can be copied only the day that has been created) </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
