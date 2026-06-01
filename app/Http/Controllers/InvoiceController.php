<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Seller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function create()
    {
        $clients = Client::all();

        $products = Product::all();

        return view('new-invoice', compact('clients', 'products'));
    }

    public function store(Request $request)
    {
        $selectedProducts = $request->products;

        if(!$selectedProducts)
        {
            return redirect()
                ->back()
                ->with('error', 'Pasirinkite bent vieną prekę.');
        }

        $totalWithoutVat = 0;

        foreach($selectedProducts as $productId)
        {
            $product = Product::find($productId);
            $quantity = $request->quantities[$productId];
            $totalWithoutVat += $product->unit_price * $quantity;
        }

        $vatAmount = 0;

        foreach($selectedProducts as $productId)
        {
            $product = Product::find($productId);

            $quantity = $request->quantities[$productId];

            $vatPercent = $request->vat[$productId];

            $lineTotal = $product->unit_price * $quantity;

            $vatAmount += $lineTotal * ($vatPercent / 100);
        }

        $totalWithVat = $totalWithoutVat + $vatAmount;

        /* Invoice number */

        $lastInvoice = Invoice::latest()->first();

        if($lastInvoice)
        {
            $lastNumber = (int) substr($lastInvoice->invoice_number, 4);
            $newNumber = $lastNumber + 1;
        }
        else
        {
            $newNumber = 1;
        }

        $invoiceNumber = 'ABC-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        /* Create invoice */

        $invoice = Invoice::create([

            'client_id' => $request->client_id,
            'invoice_number' => $invoiceNumber,
            'total_without_vat' => $totalWithoutVat,
            'vat_amount' => $vatAmount,
            'total_with_vat' => $totalWithVat
        ]);

        /* Create invoice items */

        foreach($selectedProducts as $productId)
        {
            $product = Product::find($productId);
            $quantity = $request->quantities[$productId];
            $total = $product->unit_price * $quantity;

            InvoiceItem::create([

                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->unit_price,
                'total' => $total,
                'vat_percent' => $request->vat[$productId]
            ]);
        }

        return redirect()->back()
            ->with('success', 'Sąskaita sėkmingai sukurta.');
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $client = Client::find($invoice->client_id);
        $seller = Seller::first();
        $items = InvoiceItem::where('invoice_id', $invoice->id)->get();

        return view('invoice-show', compact(
            'invoice',
            'client',
            'seller',
            'items'
        ));
    }

    public function list()
    {
        $invoices = Invoice::latest()->get();

        return view('invoice-list', compact('invoices'));
    }

    public function pdf($id)
        {
            $invoice = Invoice::findOrFail($id);

            $client = Client::find($invoice->client_id);

            $seller = Seller::first();

            $items = InvoiceItem::where('invoice_id', $invoice->id)->get();

            $pdf = Pdf::loadView('invoice-show', compact(
                'invoice',
                'client',
                'seller',
                'items'
            ))->setPaper('a4', 'portrait');

            return $pdf->download(
                $invoice->invoice_number . '.pdf'
            );
        }

    public function sendEmail(Request $request, $id)
        {
            $request->validate([
                'email' => 'required|email',
            ]);

            $invoice = Invoice::findOrFail($id);
            $client = Client::find($invoice->client_id);
            $seller = Seller::first();
            $items = InvoiceItem::where('invoice_id', $invoice->id)->get();

            $pdf = Pdf::loadView('invoice-show', compact(
                'invoice',
                'client',
                'seller',
                'items'
            ))->setPaper('a4', 'portrait');

            Mail::raw(
                $request->message ?? 'Pridedama PVM sąskaita-faktūra.',
                function($mail) use ($request, $invoice, $pdf)
                {
                    $mail->to($request->email)
                        ->subject('PVM sąskaita-faktūra ' . $invoice->invoice_number)
                        ->attachData(
                            $pdf->output(),
                            $invoice->invoice_number . '.pdf',
                            [
                                'mime' => 'application/pdf',
                            ]
                        );
                }
            );

            return redirect('/invoice-list')
                ->with('success', 'Sąskaita sėkmingai išsiųsta el. paštu.');
        }
}