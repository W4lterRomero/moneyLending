<?php

namespace App\Livewire\Finance;

use App\Models\Account;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceDashboard extends Component
{
    use WithPagination;

    // Account Modal
    public bool $showAccountModal = false;
    public ?int $editingAccountId = null;
    public string $accountName = '';
    public string $accountInitialBalance = '0';
    public string $accountColor = '#0ea5e9';

    // Transaction Modal
    public bool $showTransactionModal = false;
    public string $transactionType = 'expense';
    public ?int $transactionAccountId = null;
    public string $transactionAmount = '';
    public string $transactionCategory = '';
    public string $transactionDescription = '';
    public string $transactionDate = '';
    
    // Category Modal
    public bool $showCategoryModal = false;
    public string $newCategoryName = '';

    #[Url]
    public string $filterType = '';

    // Date Range Filter
    #[Url]
    public string $dateRange = 'this_month';
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        $this->transactionDate = now()->format('Y-m-d');
    }

    // Reset page when filters change
    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->resetPage();
    }

    // Helper to get current filter label
    public function getDateRangeLabel(): string
    {
        return match($this->dateRange) {
            'this_month' => now()->translatedFormat('F Y'),
            'last_30' => 'Últimos 30 días',
            'all' => 'Todo el historial',
            'custom' => $this->startDate && $this->endDate 
                ? date('d/m/Y', strtotime($this->startDate)) . ' - ' . date('d/m/Y', strtotime($this->endDate))
                : 'Rango personalizado',
            default => 'Este mes',
        };
    }

    // ========== ACCOUNT METHODS ==========

    public function openAccountModal(?int $id = null): void
    {
        $this->resetAccountForm();
        
        if ($id) {
            $account = Account::find($id);
            if ($account) {
                $this->editingAccountId = $id;
                $this->accountName = $account->name;
                $this->accountInitialBalance = (string) $account->initial_balance;
                $this->accountColor = $account->color;
            }
        }
        
        $this->showAccountModal = true;
    }

    public function saveAccount(): void
    {
        $this->validate([
            'accountName' => 'required|string|max:255',
            'accountInitialBalance' => 'required|numeric|min:0',
            'accountColor' => 'required|string',
        ]);

        $data = [
            'name' => $this->accountName,
            'initial_balance' => (float) $this->accountInitialBalance,
            'color' => $this->accountColor,
        ];

        if ($this->editingAccountId) {
            $account = Account::find($this->editingAccountId);
            $oldInitial = $account->initial_balance;
            $account->update($data);
            
            if ($oldInitial != $data['initial_balance']) {
                $account->current_balance += ($data['initial_balance'] - $oldInitial);
                $account->save();
            }
        } else {
            $data['current_balance'] = $data['initial_balance'];
            Account::create($data);
        }

        $this->showAccountModal = false;
        $this->resetAccountForm();
    }

    public function deleteAccount(int $id): void
    {
        Account::destroy($id);
    }

    protected function resetAccountForm(): void
    {
        $this->editingAccountId = null;
        $this->accountName = '';
        $this->accountInitialBalance = '0';
        $this->accountColor = '#0ea5e9';
    }

    // ========== TRANSACTION METHODS ==========

    public function openTransactionModal(string $type = 'expense', ?int $accountId = null): void
    {
        $this->resetTransactionForm();
        $this->transactionType = $type;
        $this->transactionAccountId = $accountId ?? Account::first()?->id;
        $this->transactionDate = now()->format('Y-m-d');
        $this->showTransactionModal = true;
    }

    public function saveTransaction(): void
    {
        $this->validate([
            'transactionAccountId' => 'required|exists:accounts,id',
            'transactionAmount' => 'required|numeric|min:0.01',
            'transactionCategory' => 'required|string|max:255',
            'transactionDate' => 'required|date',
        ]);

        FinanceTransaction::create([
            'account_id' => $this->transactionAccountId,
            'type' => $this->transactionType,
            'amount' => (float) $this->transactionAmount,
            'category' => $this->transactionCategory,
            'description' => $this->transactionDescription,
            'transaction_date' => $this->transactionDate,
        ]);

        $this->showTransactionModal = false;
        $this->resetTransactionForm();
    }

    public function deleteTransaction(int $id): void
    {
        FinanceTransaction::destroy($id);
    }

    protected function resetTransactionForm(): void
    {
        $this->transactionAccountId = null;
        $this->transactionAmount = '';
        $this->transactionCategory = '';
        $this->transactionDescription = '';
        $this->transactionDate = now()->format('Y-m-d');
    }

    // ========== CATEGORY METHODS ==========
    
    public function openCategoryModal(): void
    {
        $this->newCategoryName = '';
        $this->showCategoryModal = true;
    }
    
    public function addCategory(): void
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255',
        ]);
        
        FinanceCategory::firstOrCreate([
            'name' => trim($this->newCategoryName),
            'type' => $this->transactionType,
        ]);
        
        $this->newCategoryName = '';
        $this->showCategoryModal = false;
    }
    
    public function deleteCategory(int $id): void
    {
        FinanceCategory::destroy($id);
    }

    // ========== COMPUTED DATA ==========

    public function render()
    {
        $accounts = Account::where('is_active', true)
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        $transactionsQuery = FinanceTransaction::with('account')
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->dateRange === 'this_month', fn($q) => 
                $q->whereMonth('transaction_date', now()->month)
                  ->whereYear('transaction_date', now()->year)
            )
            ->when($this->dateRange === 'last_30', fn($q) => 
                $q->where('transaction_date', '>=', now()->subDays(30))
            )
            ->when($this->dateRange === 'custom' && $this->startDate && $this->endDate, fn($q) =>
                $q->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            )
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        $transactions = $transactionsQuery->paginate(15);

        // Summary stats (for current filter period)
        $totalBalance = Account::where('is_active', true)->sum('current_balance');
        $todayIncome = FinanceTransaction::where('type', 'income')
            ->whereDate('transaction_date', today())
            ->sum('amount');
        $todayExpense = FinanceTransaction::where('type', 'expense')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        // Categories from database
        $incomeCategories = FinanceCategory::income()->orderBy('name')->get();
        $expenseCategories = FinanceCategory::expense()->orderBy('name')->get();

        return view('livewire.finance.finance-dashboard', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'totalBalance' => $totalBalance,
            'todayIncome' => $todayIncome,
            'todayExpense' => $todayExpense,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
        ]);
    }
}
