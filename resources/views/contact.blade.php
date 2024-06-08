@extends('layouts.layout')
@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen py-2 bg-gray-100">
        <div class="flex flex-col items-center justify-center space-y-5 text-center">
            <h1 class="text-4xl font-bold text-gray-900">Contact Us</h1>
            <p class="mt-2 text-lg text-gray-600">Have any questions? Feel free to reach out! We're always here to help.</p>
            <a href="mailto:example@store.com"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-lg transform transition duration-500 hover:bg-blue-700 hover:scale-110">Email
                Us</a>
        </div>
    </div>
@endsection
@section('title')
    Contact
@endsection
