<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shop.index', ['shop' => shop::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shop.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ItemName' => 'required',
            'ItemOrigin' => 'required',
            'ItemPrice' => ['required', 'integer'],
            'ItemImage' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'ItemDescription' => 'required',
            'ItemQuantity' => ['required', 'integer']
        ]);
        $Shop = new Shop();
        $Shop->name = $request->input('ItemName');
        $Shop->origin = $request->input('ItemOrigin');
        $Shop->price = $request->input('ItemPrice');
        $Shop->image = $request->input('ItemImage');
        $Shop->description = $request->input('ItemDescription');
        $Shop->quantity = $request->input('ItemQuantity');

        $imageName = time() . '.' . $request->ItemImage->extension();
        $request->ItemImage->move(public_path('images'), $imageName);
        $Shop->image = $imageName; // Use $Shop->image instead of $Shop->ItemImage 

        $Shop->save();
        return redirect()->route('shop.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Shop::findOrFail($id);
        $paymentStatus = \App::make('App\Http\Controllers\CheckoutController')->getPaymentStatus($item->id);
        return view('shop.show', ['shop' => $item, 'paymentStatus' => $paymentStatus]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Shop::findOrFail($id);
        return view('shop.edit', ['shop' => $item]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ItemName' => 'required',
            'ItemOrigin' => 'required',
            'ItemPrice' => ['required', 'integer'],
            'ItemImage' => 'required',
            'ItemDescription' => 'required',
            'ItemQuantity' => ['required', 'integer']
        ]);
        $ShopUpdate = Shop::findOrFail($id);
        $ShopUpdate->name = $request->input('ItemName');
        $ShopUpdate->origin = $request->input('ItemOrigin');
        $ShopUpdate->price = $request->input('ItemPrice');
        $ShopUpdate->image = $request->input('ItemImage');
        $ShopUpdate->description = $request->input('ItemDescription');
        $ShopUpdate->quantity = $request->input('ItemQuantity');

        $ShopUpdate->save();
        // Find and delete payment record for the item
        $payment = \App\Models\Payment::where('item_id', $id)->first();
        if ($payment) {
            $payment->delete();
        }

        return redirect()->route('shop.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Shop = Shop::findOrFail($id);
        $Shop->delete();
        return redirect()->route('shop.index');
    }
}