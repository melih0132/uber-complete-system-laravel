<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class ProductController extends Controller
{
    public function create()
    {
        return view('add-produit');
    }
}
