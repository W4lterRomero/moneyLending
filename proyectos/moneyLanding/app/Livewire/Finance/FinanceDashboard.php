<?php

namespace App\Livewire\Finance;

use App\Models\Account;
use App\Models\FinanceTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;

class FinanceDashboard extends Component
{
    // Account Modal
    public bool $showAccountModal = false;
    public ?int $editingAccountId = null;
    public string $accountName = '';
    public string $accountInitialBalance = '0';
    public string $accountColor = '#0ea5e9';

    // Transaction Modal
    public bool $showTransactionModal = false;
    public string $transactionType = 'expense'; // 'income' or 'expense'
    public ?int $transactionAccountId = null;
    public string $transactionAmount = '';
    public string $transactionCategory = '';
    public string $transactionDescription = '';
    public string $transactionDate = '';

    #[Url]
    public string $filterType = ''; // '', 'income', 'expense'

    public function mount(): void
    {
        $this->transactionDate = now()->format('Y-m-d');
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
            
            // Adjust current_balance if initial changed
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

    // ========== COMPUTED DATA ==========

    public function render()
    {
        $accounts = Account::where('is_active', true)
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        $transactionsQuery = FinanceTransaction::with('account')
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(20);

        $transactions = $transactionsQuery->get();

        // Summary stats
        $totalBalance = Account::where('is_active', true)->sum('current_balance');
        $todayIncome = FinanceTransaction::where('type', 'income')
            ->whereDate('transaction_date', today())
            ->sum('amount');
        $todayExpense = FinanceTransaction::where('type', 'expense')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        $categories = FinanceTransaction::commonCategories();

        return view('livewire.finance.finance-dashboard', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'totalBalance' => $totalBalance,
            'todayIncome' => $todayIncome,
            'todayExpense' => $todayExpense,
            'categories' => $categories,
        ]);
    }
}
