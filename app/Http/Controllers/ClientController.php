<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return view('create-client');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        Client::create([
            'company_name' => $request->company_name,
            'company_code' => $request->company_code,
            'address' => $request->address,
            'vat_code' => $request->vat_code,
            'phone' => $request->phone,
        ]);

        return redirect('/create-client')
            ->with('success', 'Klientas sėkmingai sukurtas');
    }

    public function list()
    {
        $clients = Client::all();

        return view('client-list', compact('clients'));
    }

    public function delete($id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return redirect('/client-list')
            ->with('success', 'Klientas ištrintas');
    }

    public function update(Request $request, $id)
{
    $client = Client::findOrFail($id);

    $client->update([
        'company_name' => $request->company_name,
        'company_code' => $request->company_code,
        'address' => $request->address,
        'vat_code' => $request->vat_code,
        'phone' => $request->phone,
    ]);

    return redirect('/client-list')
        ->with('success', 'Klientas sėkmingai atnaujintas');
}
}