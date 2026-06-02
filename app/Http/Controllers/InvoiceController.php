<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Seller;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\InvoiceSummaryExport;

use NumberToWords\NumberToWords;

class InvoiceController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();

        return view('new-invoice', compact('clients', 'products'));
    }

    private function amountToWords($amount)
    {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('lt');

        $euros = floor($amount);
        $cents = round(($amount - $euros) * 100);

        return [
            'amountWords' => $numberTransformer->toWords($euros),
            'cents' => $cents
        ];
    }

    public function store(Request $request)
    {
        $selectedProducts = $request->products;

        if(!$selectedProducts)
        {
            return redirect()->back()
                ->with('error', 'Pasirinkite bent vieną prekę.');
        }

        $totalWithoutVat = 0;
        $vatAmount = 0;

        foreach($selectedProducts as $productId)
        {
            $product = Product::find($productId);
            $quantity = $request->quantities[$productId];
            $vatPercent = $request->vat[$productId];

            $lineTotal = $product->unit_price * $quantity;

            $totalWithoutVat += $lineTotal;
            $vatAmount += $lineTotal * ($vatPercent / 100);
        }

        $totalWithVat = $totalWithoutVat + $vatAmount;

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

        $invoice = Invoice::create([
            'client_id' => $request->client_id,
            'invoice_number' => $invoiceNumber,
            'total_without_vat' => $totalWithoutVat,
            'vat_amount' => $vatAmount,
            'total_with_vat' => $totalWithVat
        ]);

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

        $words = $this->amountToWords($invoice->total_with_vat);
        $amountWords = $words['amountWords'];
        $cents = $words['cents'];

        return view('invoice-show', compact(
            'invoice',
            'client',
            'seller',
            'items',
            'amountWords',
            'cents'
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

        $words = $this->amountToWords($invoice->total_with_vat);
        $amountWords = $words['amountWords'];
        $cents = $words['cents'];

        $pdf = Pdf::loadView('invoice-show', compact(
            'invoice',
            'client',
            'seller',
            'items',
            'amountWords',
            'cents'
        ))->setPaper('a4', 'portrait');

        return $pdf->download($invoice->invoice_number . '.pdf');
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

        $words = $this->amountToWords($invoice->total_with_vat);
        $amountWords = $words['amountWords'];
        $cents = $words['cents'];

        $pdf = Pdf::loadView('invoice-show', compact(
            'invoice',
            'client',
            'seller',
            'items',
            'amountWords',
            'cents'
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
                        ['mime' => 'application/pdf']
                    );
            }
        );

        return redirect('/invoice-list')
            ->with('success', 'Sąskaita sėkmingai išsiųsta el. paštu.');
    }

    public function summary(Request $request)
    {
        $invoices = collect();

        if($request->start_date && $request->end_date)
        {
            $invoices = Invoice::whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date)
                ->latest()
                ->get();
        }

        return view('invoice-summary', compact('invoices'));
    }

    public function summaryPdf(Request $request)
    {
        $invoices = Invoice::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'invoice-summary-pdf',
            compact('invoices')
        )->setPaper('a4', 'landscape');

        return $pdf->download('saskaitu-suvestine.pdf');
    }
}