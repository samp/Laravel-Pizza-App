<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="app">
                        @if ($cart == null)
                            <p class="text-center">{{ 'Your cart is currently empty!' }}</p>
                            <br>
                            <div class="text-center">
                                <a href="/order" role="button" class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600">
                                    Start an order
                                </a>
                            </div>
                        @else
                            <cart v-bind:auth_user="{{ $auth_user }}" v-bind:cart="{{ json_encode($cart) }}" v-bind:errors="{{ $errors }}" v-bind:activedeals="{{ json_encode($activedeals) }}"></cart>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
