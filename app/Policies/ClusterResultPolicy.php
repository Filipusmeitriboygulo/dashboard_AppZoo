<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClusterResult;

class ClusterResultPolicy
{
    public function view(User $user, ClusterResult $result): bool
    {
        if (in_array($user->role, ['admin', 'kepala_upa', 'wakil_direktur'])) {
            return true;
        }

        if ($user->role === 'ketua_prodi') {
            return $user->studyProgram && $result->prodi === $user->studyProgram->name;
        }

        if ($user->role === 'ketua_jurusan') {
            return $user->department && $result->jurusan === $user->department->name;
        }

        return false;
    }
}
