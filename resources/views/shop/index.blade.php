@extends('layouts.layout')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8">
            <h1 class="text-4xl font-semibold text-gray-900 dark:text-white">Shop Page</h1>

        </div>
        @if (count($shop) > 0)
            <div class="mt-8">
                <ul>
                    @foreach ($shop as $shop)
                        <a href="{{ route('shop.show', ['shop' => $shop['id']]) }}">
                            <li>
                                <p>{{ $shop['name'] }} ({{ $shop['origin'] }}) - <strong> {{ $shop['price'] }}$ </strong>
                                </p>




                            </li>
                        </a>
                    @endforeach
                </ul>
            </div>
        @endif



    </div>
@endsection
@section('title')
    Shop Page
@endsection
