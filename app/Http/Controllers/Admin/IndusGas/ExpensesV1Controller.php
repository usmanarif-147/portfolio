<?php

namespace App\Http\Controllers\Admin\IndusGas;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ExpensesV1Controller extends Controller
{
    public function __invoke(): View
    {
        return view('admin.indus-gas.expenses-v1.index');
    }
}
