<?php

namespace App\Http\Controllers;

use App\Models\IndusGas\LpgPlantEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class IndusGasLpgPlantPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $entries = LpgPlantEntry::query()
            ->whereBetween('entry_date', [$data['from'], $data['to']])
            ->orderBy('entry_date')
            ->get(['entry_date', 'rate_per_kg', 'rate_45_4_kg']);

        return Pdf::loadView('indus-gas.pdf.lpg-plant-rates', compact('entries', 'data'))
            ->setPaper('a4')
            ->download('lpg-plant-rates-'.$data['from'].'-to-'.$data['to'].'.pdf');
    }
}
