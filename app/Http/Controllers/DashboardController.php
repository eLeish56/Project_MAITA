<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'total_items' => Item::all()->count(),
            'total_transactions' => Transaction::all()->count(),
            'total_income' => Transaction::sum('total'),
            'income_today' => Transaction::whereDate('updated_at', now())->sum('total'),
            'income_this_month' => Transaction::whereMonth('updated_at', now())->sum('total')
        ]);
    }

    public function getMonthlyIncome(Request $request)
    {
        $year = $request->input('year', date('Y'));
        return response()->json($this->getMonthlyData($year));
    }

    protected function getMonthlyData($year)
    {
        // Get monthly income and sales count
        $monthlyData = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as sales_count')
        )
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Initialize arrays with zeros
        $income = array_fill(0, 12, 0);
        $sales = array_fill(0, 12, 0);

        // Fill in actual data
        foreach ($monthlyData as $record) {
            $income[$record->month - 1] = (int)$record->total;
            $sales[$record->month - 1] = (int)$record->sales_count;
        }

        return [
            'income' => $income,
            'sales' => $sales
        ];
    }
}
