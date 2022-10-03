<?php

namespace App\Policies;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobVacancyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, JobVacancy $vacancy): bool
    {
        return $user->getKey() == $vacancy->user_id;
    }

    public function delete(User $user, JobVacancy $vacancy): bool
    {
       return $user->getKey() == $vacancy->user_id;
    }

    public function responseCreate(User $user, JobVacancy $vacancy): bool
    {
        return $user->getKey() !== $vacancy->user_id;
    }
}
