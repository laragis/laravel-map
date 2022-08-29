<?php
namespace TungTT\LaravelMap\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use TungTT\LaravelMap\Models\MapMap;

class MapMapPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, MapMap $model): bool
    {
        return true;
    }

    public function store(User $user = null): bool
    {
        return true;
    }

    public function storeBulk(User $user): bool
    {
        return true;
    }

    public function update(User $user = null, MapMap $model): bool
    {
        return true;
    }

    public function updateBulk(User $user, MapMap $model): bool
    {
        return true;
    }

    public function deleteBulk(User $user, MapMap $model): bool
    {
        return true;
    }

    public function delete(User $user, MapMap $model): bool
    {
        return true;
    }
}
