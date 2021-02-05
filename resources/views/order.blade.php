@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Order') }}</div>

                    <div class="card-body">
                        
                        <orderform v-bind:auth_user="@json($auth_user)" v-bind:pizzas="{{ $pizzas }}" v-bind:toppings="{{ $toppings }}"></orderform>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
