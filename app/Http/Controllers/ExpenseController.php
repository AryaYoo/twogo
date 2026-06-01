<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $trip->load(['expenses.payer', 'expenses.splits.user']);
        
        $totalSpent = $trip->expenses->sum('amount');
        $remainingBudget = max(0, $trip->total_budget - $totalSpent);
        
        $categoryBreakdown = $trip->expenses->groupBy('category')->map(function ($row) {
            return $row->sum('amount');
        });

        $splitBudgetExpenseExists = $trip->expenses()->where('title', 'Split Budget')->exists();

        // Calculate settlement (who owes who)
        $balances = [];
        foreach ($trip->members as $member) {
            $balances[$member->id] = ['user' => $member, 'balance' => 0];
        }

        foreach ($trip->expenses as $expense) {
            // Payer gets positive balance (people owe them)
            $balances[$expense->paid_by]['balance'] += $expense->amount;
            
            // Splitters get negative balance (they owe)
            foreach ($expense->splits as $split) {
                $balances[$split->user_id]['balance'] -= $split->amount;
            }
        }

        return view('expenses.index', compact('trip', 'totalSpent', 'remainingBudget', 'categoryBreakdown', 'balances', 'splitBudgetExpenseExists'));
    }

    public function dashboard()
    {
        $completedTrips = Auth::user()
            ->trips()
            ->where('status', 'completed')
            ->with('expenses')
            ->orderByDesc('end_date')
            ->get();

        return view('expenses.dashboard', compact('completedTrips'));
    }

    public function create(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);
        
        $members = $trip->members;
        return view('expenses.create', compact('trip', 'members'));
    }

    public function store(Request $request, Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|in:akomodasi,transportasi,kuliner,tiket,belanja,lainnya',
            'expense_date' => 'required|date',
            'split_type' => 'required|in:equal,custom,solo'
        ]);

        DB::transaction(function () use ($request, $trip) {
            $expense = Expense::create([
                'trip_id' => $trip->id,
                'paid_by' => Auth::id(),
                'title' => $request->title,
                'amount' => $request->amount,
                'category' => $request->category,
                'expense_date' => $request->expense_date,
                'split_type' => $request->split_type,
                'notes' => $request->notes,
            ]);

            if ($request->split_type === 'equal') {
                $memberCount = $trip->members->count();
                $splitAmount = $request->amount / $memberCount;
                
                foreach ($trip->members as $member) {
                    ExpenseSplit::create([
                        'expense_id' => $expense->id,
                        'user_id' => $member->id,
                        'amount' => $splitAmount,
                        'is_settled' => $member->id === Auth::id() // payer is automatically settled
                    ]);
                }
            } elseif ($request->split_type === 'solo') {
                ExpenseSplit::create([
                    'expense_id' => $expense->id,
                    'user_id' => Auth::id(),
                    'amount' => $request->amount,
                    'is_settled' => true
                ]);
            }
            // Custom split not fully implemented in UI for simplicity in this version
        });

        return redirect()->route('expenses.index', $trip)->with('success', 'Pengeluaran dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $trip = $expense->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $expense->delete();
        
        return back()->with('success', 'Pengeluaran dihapus.');
    }
}
