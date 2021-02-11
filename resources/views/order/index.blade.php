@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Order') }}</div>

                    <div class="card-body">
                        
                        <orderform v-bind:auth_user="{{ $auth_user }}" v-bind:pizzas="{{ $pizzas }}" v-bind:toppings="{{ $toppings }}" v-bind:errors="{{ $errors }}"></orderform>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
