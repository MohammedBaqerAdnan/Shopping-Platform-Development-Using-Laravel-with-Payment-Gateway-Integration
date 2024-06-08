@extends('layouts.layout')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8">
            <h1 class="text-4xl font-semibold text-gray-900 dark:text-white">Items</h1>

        </div>
        <div class="mt-8">
            <p><strong>Item Name:</strong>{{ $shop['name'] }} </p>
            <p><strong>Item Origin:</strong>{{ $shop['origin'] }} </p>
            <p><strong>Item Price:</strong>{{ $shop['price'] }}$ </p>
            <p><strong>Item Description:</strong>{{ $shop['description'] }} </p>
            <p><strong>Item Quantity:</strong>{{ $shop['quantity'] }} </p>
            <p><strong>Item Image:</strong>
                <img src="{{ asset('images/' . $shop->image) }}" alt="Image description"> <!-- use $shop instead of $Shop -->
            </p>
        </div>

        <a href="{{ route('shop.edit', $shop->id) }}" class="edit-button">edit</a>

        <form action="{{ route('shop.destroy', $shop->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="delete-button" value="delete">
        </form>
        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="price" value="{{ $shop['price'] }}">
            <input type="hidden" name="item_id" value="{{ $shop->id }}">
            <input type="checkbox" id="save_card" name="save_card" value="1">
            <label for="save_card">Save Card for Future Payments</label>
            <input type="submit" class="checkout-button" value="Checkout">
        </form>
        <form action="{{ route('update_payment_status') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $shop->id }}">
            <input type="radio" id="processed_yes" name="processed" value="1">
            <label for="processed_yes">Yes</label>
            <input type="radio" id="processed_no" name="processed" value="0">
            <label for="processed_no">No</label>
            <input type="submit" class="update-payment-status-button" value="Update Payment Status">
        </form>

        <p><strong>Payment Status: </strong>{{ $paymentStatus }}</p>


        {{-- $paymentStatus = CheckoutController::getPaymentStatus($shop->id);

        <p class="text-2xl font-semibold text-gray-900 dark:text-white">Payment Status</p>
        @if ($paymentStatus == 'Payment is successful')
            <p class="text-green-500 text-xl">This item has been paid for successfully.</p>
        @else
            <p class="text-red-600 text-xl">Payment for this item is not successful.</p>
        @endif --}}

    </div>
@endsection
@section('title')
    Items
@endsection
