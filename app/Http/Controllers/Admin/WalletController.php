<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $query = UserWallet::with('user')
            ->withCount('transactions')
            ->orderByDesc('updated_at');

        if ($user = $request->string('user')->toString()) {
            $query->where(function ($q) use ($user): void {
                if (is_numeric($user)) {
                    $q->where('user_id', (int) $user);
                }

                $q->orWhere('external_user_id', 'like', "%{$user}%")
                    ->orWhereHas('user', function ($uq) use ($user): void {
                        $uq->where('email', 'like', "%{$user}%")
                            ->orWhere('name', 'like', "%{$user}%");
                    });
            });
        }

        $wallets = $query->paginate(25)->withQueryString();

        return view('admin.wallets.index', [
            'title' => 'Wallets de usuarios',
            'wallets' => $wallets,
            'filters' => [
                'user' => $user ?? null,
            ],
        ]);
    }

    public function show(UserWallet $wallet, Request $request)
    {
        $wallet->load('user');

        $txQuery = $wallet->transactions()->orderByDesc('created_at');

        if ($type = $request->string('type')->toString()) {
            $txQuery->where('type', $type);
        }

        if ($from = $request->string('from')->toString()) {
            $txQuery->whereDate('created_at', '>=', Carbon::parse($from));
        }

        if ($to = $request->string('to')->toString()) {
            $txQuery->whereDate('created_at', '<=', Carbon::parse($to));
        }

        $transactions = $txQuery->paginate(25)->withQueryString();

        return view('admin.wallets.show', [
            'title' => 'Wallet usuario #'.$wallet->user_id,
            'wallet' => $wallet,
            'transactions' => $transactions,
            'filters' => [
                'type' => $type ?? null,
                'from' => $from ?? null,
                'to' => $to ?? null,
            ],
        ]);
    }
}
