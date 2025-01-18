<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class JuridiqueController extends Controller
{
    public function showAnonymisationForm(Request $request)
    {
        $searchQuery = $request->input('search');

        $clients = Client::when($searchQuery, function ($query, $searchQuery) {
            return $query->where('prenomuser', 'like', "%{$searchQuery}%")
                ->orWhere('nomuser', 'like', "%{$searchQuery}%")
                ->orWhere('emailuser', 'like', "%{$searchQuery}%");
        })
            ->orderBy('demande_suppression', 'desc')
            ->paginate(10);

        return view('juridique.anonymisation', compact('clients', 'searchQuery'));
    }

    public function demandeSuppression(Request $request)
    {
        $userSession = $request->session()->get('user');
        $idclient = $userSession['id'];
        $client = Client::findOrFail($idclient);

        $client->update(['demande_suppression' => true]);

        $request->session()->forget('user');

        return redirect()->route('accueil')->with('success', 'Votre demande de suppression a été enregistrée.');
    }

    public function anonymise(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:client,idclient',
        ]);

        $clientIds = $request->input('client_ids');
        $clients = Client::whereIn('idclient', $clientIds)->get();

        foreach ($clients as $client) {
            $this->anonymiseClient($client);
        }

        return redirect()->route('juridique.anonymisation')->with('success', 'Les données des clients sélectionnés ont été anonymisées.');
    }

    public function anonymiseClient(Client $client)
    {
        $clientId = $client->idclient;

        DB::statement('CALL anonymise_client(?)', [$clientId]);
    }
}
