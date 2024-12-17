<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomuser' => 'required|string|max:255',
            'emailuser' => 'required|email|unique:client,emailuser',
            'motdepasseuser' => 'required|min:8|confirmed',
        ]);
    }
    public function showAccount()
    {
        $user = Auth::user();
        return view('client.account', compact('user'));
    }
    
}
