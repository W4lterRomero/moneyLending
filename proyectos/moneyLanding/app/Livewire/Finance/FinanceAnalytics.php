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
    public string $period = 'month'; // week, month, year, all
    
    #[Url]
    public string $accountFilter = '';
    
    public function render()
    {
        $accounts = Account::where('is_active', true)->get();
        
        // Date range based on period
        $startDate = match($this->period) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => null,
        };
        
        // Build query
        $query = FinanceTransaction::query()->with('account');
        
        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        
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
        
        // Daily totals for simple chart (last 7 days or period)
        $dailyData = $this->getDailyData($startDate);
        
        // Account balances
        $totalBalance = $accounts->sum('current_balance');
        
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
        ]);
    }
    
    private function getDailyData($startDate): array
    {
        $days = $this->period === 'week' ? 7 : ($this->period === 'month' ? 30 : 12);
        $groupBy = $this->period === 'year' ? 'month' : 'day';
        
        $data = [];
        
        if ($groupBy === 'day') {
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dayTransactions = FinanceTransaction::whereDate('transaction_date', $date->toDateString());
                
                if ($this->accountFilter) {
                    $dayTransactions->where('account_id', $this->accountFilter);
                }
                
                $dayTransactions = $dayTransactions->get();
                
                $data[] = [
                    'label' => $date->format('d'),
                    'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                    'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                ];
            }
        } else {
            // Monthly grouping for year view
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthTransactions = FinanceTransaction::whereYear('transaction_date', $month->year)
                    ->whereMonth('transaction_date', $month->month);
                
                if ($this->accountFilter) {
                    $monthTransactions->where('account_id', $this->accountFilter);
                }
                
                $monthTransactions = $monthTransactions->get();
                
                $data[] = [
                    'label' => $month->format('M'),
                    'income' => $monthTransactions->where('type', 'income')->sum('amount'),
                    'expense' => $monthTransactions->where('type', 'expense')->sum('amount'),
                ];
            }
        }
        
        return $data;
    }
    
    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }
    
    public function setAccount(string $accountId): void
    {
        $this->accountFilter = $accountId;
    }
}
