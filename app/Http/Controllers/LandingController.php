<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;

class LandingController extends Controller
{
    public function index()
    {
        $templates = BingoCard::query()
            ->where('is_template', true)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('landing', [
            'templates' => $templates,
        ]);
    }
}

