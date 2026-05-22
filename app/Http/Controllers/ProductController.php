<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('create-product');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'measurement_unit' => 'required',
            'unit_price' => 'required'
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'measurement_unit' => $request->measurement_unit,
            'unit_price' => $request->unit_price
        ]);

        return redirect('/create-product')
            ->with('success', 'Prekė sėkmingai sukurta');
    }

    public function list()
    {
        $products = Product::all();

        return view('product-list', compact('products'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'product_name' => $request->product_name,
            'measurement_unit' => $request->measurement_unit,
            'unit_price' => $request->unit_price
        ]);

        return redirect('/product-list')
            ->with('success', 'Prekė sėkmingai atnaujinta');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect('/product-list')
            ->with('success', 'Prekė ištrinta');
    }
}