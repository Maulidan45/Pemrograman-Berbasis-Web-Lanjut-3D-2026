<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view the loan.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole(['admin', 'petugas'])
            || (int) $peminjaman->user_id === (int) $user->id;
    }
}