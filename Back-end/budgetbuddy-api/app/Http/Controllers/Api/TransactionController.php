<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Convert a transaction amount to user's default currency (TND)
     */
    public function convertCurrency(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from'   => 'required|string|max:5',
            'to'     => 'required|string|max:5',
        ]);

        $amount = floatval($request->amount);
        $from = strtoupper(trim($request->from));
        $to = strtoupper(trim($request->to));

        // Log the conversion attempt
        Log::info('Currency conversion requested', [
            'amount' => $amount,
            'from' => $from,
            'to' => $to,
            'user_id' => auth()->id()
        ]);

        $converted = $this->currencyService->convert($amount, $from, $to);

        // Debug logging
        Log::info('Currency conversion result', [
            'original' => $amount,
            'from' => $from,
            'to' => $to,
            'converted' => $converted,
            'service_used' => get_class($this->currencyService)
        ]);

        if ($converted === null || $converted === 0) {
            Log::error('Currency conversion failed or returned zero', [
                'amount' => $amount,
                'from' => $from,
                'to' => $to,
                'converted' => $converted
            ]);
            
            return response()->json([
                'error' => 'Currency conversion failed or returned invalid result',
                'debug' => [
                    'original' => $amount,
                    'from' => $from,
                    'to' => $to,
                    'converted' => $converted
                ]
            ], 500);
        }

        return response()->json([
            'original' => $amount,
            'from' => $from,
            'to' => $to,
            'converted' => $converted,
            'rate' => round($converted / $amount, 4)
        ]);
    }

    /**
     * List available currencies
     */
    public function currencies()
    {
        try {
            $currencies = $this->currencyService->currencies();
            
            Log::info('Currencies list retrieved', [
                'count' => is_array($currencies) ? count($currencies) : 0,
            ]);

            // Always return success - never return error for currencies
            return response()->json($currencies);
            
        } catch (\Exception $e) {
            Log::error('Error in currencies endpoint: ' . $e->getMessage());
            
            // Return fallback currencies even on error
            return response()->json([
                'USD' => 'US Dollar',
                'EUR' => 'Euro',
                'GBP' => 'British Pound', 
                'TND' => 'Tunisian Dinar',
                'CAD' => 'Canadian Dollar',
                'MAD' => 'Moroccan Dirham'
            ]);
        }
    }

    // List all transactions of authenticated user
    public function index()
    {
        try {
            $transactions = auth()->user()->transactions()->with('category')->get();
            
            Log::info('Transactions retrieved', [
                'user_id' => auth()->id(),
                'count' => $transactions->count()
            ]);

            return response()->json($transactions);
        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Store new transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0.01',
            'currency'    => 'sometimes|string|max:5',
            'note'        => 'sometimes|string|max:500',
            'date'        => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['currency'] = $validated['currency'] ?? 'TND';

        try {
            $transaction = Transaction::create($validated);
            $transaction->load('category');

            Log::info('Transaction created', [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount
            ]);

            return response()->json([
                'message' => 'Transaction created successfully',
                'transaction' => $transaction
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to create transaction',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Show a transaction
    public function show($id)
    {
        try {
            $transaction = Transaction::where('user_id', auth()->id())
                                      ->with('category')
                                      ->findOrFail($id);
            
            return response()->json($transaction);
        } catch (\Exception $e) {
            Log::error('Error fetching transaction: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Transaction not found or access denied'
            ], 404);
        }
    }

    // Update transaction
    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

            $validated = $request->validate([
                'category_id' => 'sometimes|exists:categories,id',
                'amount'      => 'sometimes|numeric|min:0.01',
                'currency'    => 'sometimes|string|max:5',
                'note'        => 'sometimes|string|max:500',
                'date'        => 'sometimes|date',
            ]);

            $transaction->update($validated);
            $transaction->load('category');

            Log::info('Transaction updated', [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id
            ]);

            return response()->json([
                'message' => 'Transaction updated successfully',
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating transaction: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to update transaction',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Delete transaction
    public function destroy($id)
    {
        try {
            $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
            $transaction->delete();

            Log::info('Transaction deleted', [
                'user_id' => auth()->id(),
                'transaction_id' => $id
            ]);

            return response()->json(['message' => 'Transaction deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to delete transaction',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly summary for the authenticated user
     */
    public function monthlySummary($month = null, $year = null)
    {
        try {
            $userId = auth()->id();
            $month = $month ?? date('m');
            $year = $year ?? date('Y');

            $summary = DB::table('transactions')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->where('transactions.user_id', $userId)
                ->whereMonth('transactions.date', $month)
                ->whereYear('transactions.date', $year)
                ->selectRaw("
                    SUM(CASE WHEN categories.type = 'income' THEN transactions.amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN categories.type = 'expense' THEN transactions.amount ELSE 0 END) as total_expense
                ")
                ->first();

            $totalIncome = floatval($summary->total_income ?? 0);
            $totalExpense = floatval($summary->total_expense ?? 0);
            $balance = $totalIncome - $totalExpense;

            Log::info('Monthly summary generated', [
                'user_id' => $userId,
                'month' => $month,
                'year' => $year,
                'income' => $totalIncome,
                'expense' => $totalExpense,
                'balance' => $balance
            ]);

            return response()->json([
                'month' => intval($month),
                'year' => intval($year),
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'balance' => $balance
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating monthly summary: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate monthly summary',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category breakdown for Pie Chart
     */
    public function categoryBreakdown($month = null, $year = null)
    {
        try {
            $userId = auth()->id();
            $month = $month ?? date('m');
            $year = $year ?? date('Y');

            $data = DB::table('transactions')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->where('transactions.user_id', $userId)
                ->whereMonth('transactions.date', $month)
                ->whereYear('transactions.date', $year)
                ->where('categories.type', 'expense') // Only expenses for breakdown
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.name')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'total' => floatval($item->total)
                    ];
                });

            Log::info('Category breakdown generated', [
                'user_id' => $userId,
                'month' => $month,
                'year' => $year,
                'categories_count' => $data->count()
            ]);

            return response()->json([
                'month' => intval($month),
                'year' => intval($year),
                'categories' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating category breakdown: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate category breakdown',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent transactions (last N)
     */
    public function recent($limit = 5)
    {
        try {
            $limit = min($limit, 50); // Prevent excessive queries
            $transactions = auth()->user()
                ->transactions()
                ->with('category')
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            Log::info('Recent transactions retrieved', [
                'user_id' => auth()->id(),
                'limit' => $limit,
                'count' => $transactions->count()
            ]);

            return response()->json($transactions);
        } catch (\Exception $e) {
            Log::error('Error fetching recent transactions: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch recent transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}