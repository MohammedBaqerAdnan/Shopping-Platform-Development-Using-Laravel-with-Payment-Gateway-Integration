@extends('layouts.layout')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8">
            <h1 class="text-4xl font-semibold text-gray-900 dark:text-white">edits Items</h1>


        </div>
        <div class="flex justify-center pt-8">
            <form action="{{ route('shop.update', ['shop' => $shop->id]) }}" method="POST" class="form bg-white p-6 border-1">
                @csrf
                @method('PUT')
                <div>
                    <label for="ItemName" class="text-sm">Item Name</label>
                    <input type="text" class="text-lg border-1" id="ItemName" name="ItemName"
                        value="{{ $shop->name }} ">
                    @error('ItemName')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="ItemOrigin" class="text-sm">Item Origin</label>
                    <input type="text" class="text-lg border-1" id="ItemOrigin" name="ItemOrigin"
                        value="{{ $shop->origin }}">
                    @error('ItemOrigin')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="ItemPrice" class="text-sm">Item Price</label>
                    <input type="number" class="text-lg border-1" id="ItemPrice" name="ItemPrice"
                        value="{{ $shop->price }}">
                    @error('ItemPrice')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="ItemImage" class="text-sm">Item Image</label>
                    <input type="text" class="text-lg border-1" id="ItemImage" name="ItemImage"
                        value="{{ $shop->image }}">
                    @error('ItemImage')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="ItemDescription" class="text-sm">Item Description</label>
                    <input type="text" class="text-lg border-1" id="ItemDescription" name="ItemDescription"
                        value="{{ $shop->description }}">
                    @error('ItemDescription')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="ItemQuantity" class="text-sm">Item Quantity</label>
                    <input type="number" class="text-lg border-1" id="ItemQuantity" name="ItemQuantity"
                        value="{{ $shop->quantity }}">
                    @error('ItemQuantity')
                        <div class="form-error text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <button type="submit">Submit</button>
                </div>
            </form>




        </div>




    </div>
@endsection
@section('title')
    edit Items
@endsection
