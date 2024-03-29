<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Success') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="app">
                        @if ($cart == null)
                            <p>Please <a href="/order">place an order</a> before submitting.</p>
                        @else
                        <?php //ddd($method) ?>
                            <success v-bind:cart="{{ json_encode($cart) }}" v-bind:method="{{ json_encode($method) }}" v-bind:finalprice="{{ $finalprice }}">
                            </success>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
