@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Cart') }}</div>
                    <div class="card-body">
                        @if ($cart == null)
                            {{ "Your cart is currently empty!" }}
                        @else
                            <cart v-bind:cart="{{ json_encode($cart) }}"></cart>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
