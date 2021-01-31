@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Order') }}</div>

                    <div class="card-body">
                        <order v-bind:pizzas="{{ $pizzas }}" v-bind:toppings="{{ $toppings }}"></order>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
