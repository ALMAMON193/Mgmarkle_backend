<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Payment;
use App\Models\SubCategory;
use App\Models\User;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $totalUsersCount = User::count();
        $totalCategoriesCount = Category::count();
        $totalSubCategoriesCount = SubCategory::count();
        $totalPaymentsCount = Payment::count();
        $totalRevenueAmount = Payment::where('status', 'success')->sum('amount');

        // Fetch recent payments from database with user relation
        $recentTransactions = Payment::with('user')->latest()->take(9)->get();

        return view('livewire.dashboard.overview', [
            'totalUsersCount' => $totalUsersCount,
            'totalCategoriesCount' => $totalCategoriesCount,
            'totalSubCategoriesCount' => $totalSubCategoriesCount,
            'totalPaymentsCount' => $totalPaymentsCount,
            'totalRevenueAmount' => $totalRevenueAmount,
            'recentTransactions' => $recentTransactions,
        ])->layout('layouts.app');
    }
}

