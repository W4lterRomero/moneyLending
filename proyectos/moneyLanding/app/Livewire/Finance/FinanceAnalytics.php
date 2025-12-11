<?php

namespace App\Livewire\Finance;

use App\Models\Account;
use App\Models\FinanceTransaction;
use Livewire\Component;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class FinanceAnalytics extends Component
{
    #[Url]
    public string $startDate = '';
    
    #[Url]
    public string $endDate = '';
    
    #[Url]
    public string $accountFilter = '';
    
    public function mount(): void
    {
        // Default to current month if no dates set
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->format('Y-m-d');
        }
    }
    
    public function setQuickRange(string $range): void
    {
        $this->endDate = Carbon::now()->format('Y-m-d');
        
        $this->startDate = match($range) {
            'week' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'month' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'year' => Carbon::now()->startOfYear()->format('Y-m-d'),
            'all' => Carbon::now()->subYears(10)->format('Y-m-d'),
            default => Carbon::now()->startOfMonth()->format('Y-m-d'),
        };
    }
    
    public function render()
    {
        $accounts = Account::where('is_active', true)->get();
        
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();
        
        // Build query
        $query = FinanceTransaction::query()
            ->with('account')
            ->whereBetween('transaction_date', [$start, $end]);
        
        if ($this->accountFilter) {
            $query->where('account_id', $this->accountFilter);
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->get();
        
        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netFlow = $totalIncome - $totalExpense;
        
        // Category breakdown
        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category')
            ->map(fn($items) => $items->sum('amount'))
            ->sortDesc()
            ->take(5);
            
        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category')
            ->map(fn($items) => $items->sum('amount'))
            ->sortDesc()
            ->take(5);
        
        // Daily totals for chart
        $dailyData = $this->getDailyData($start, $end);
        
        // Account balances
        $totalBalance = $accounts->sum('current_balance');
        
        // Period label
        $periodLabel = $start->format('d M') . ' - ' . $end->format('d M Y');
        
        return view('livewire.finance.finance-analytics', [
            'accounts' => $accounts,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netFlow' => $netFlow,
            'totalBalance' => $totalBalance,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'dailyData' => $dailyData,
            'transactionCount' => $transactions->count(),
            'periodLabel' => $periodLabel,
        ]);
    }
    
    private function getDailyData($start, $end): array
    {
        $daysDiff = $start->diffInDays($end);
        $data = [];
        
        // Group by month if range > 60 days, otherwise by day
        if ($daysDiff > 60) {
            $current = $start->copy()->startOfMonth();
            while ($current <= $end) {
                $monthEnd = $current->copy()->endOfMonth();
                if ($monthEnd > $end) $monthEnd = $end;
                
                $monthTransactions = FinanceTransaction::query()
                    ->whereBetween('transaction_date', [$current, $monthEnd]);
                
                if ($this->accountFilter) {
                    $monthTransactions->where('account_id', $this->accountFilter);
                }
                
                $monthTransactions = $monthTransactions->get();
                
                $data[] = [
                    'label' => $current->format('M'),
                    'income' => $monthTransactions->where('type', 'income')->sum('amount'),
                    'expense' => $monthTransactions->where('type', 'expense')->sum('amount'),
                ];
                
                $current->addMonth()->startOfMonth();
            }
        } else {
            $current = $start->copy();
            while ($current <= $end) {
                $dayTransactions = FinanceTransaction::query()
                    ->whereDate('transaction_date', $current->toDateString());
                
                if ($this->accountFilter) {
                    $dayTransactions->where('account_id', $this->accountFilter);
                }
                
                $dayTransactions = $dayTransactions->get();
                
                $data[] = [
                    'label' => $current->format('d'),
                    'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                    'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                ];
                
                $current->addDay();
            }
        }
        
        return $data;
    }
}
