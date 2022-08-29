<?php
namespace TungTT\LaravelMap\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use TungTT\LaravelMap\Models\MapBookmark;

class MapBookmarkPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, MapBookmark $model): bool
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

    public function update(User $user = null, MapBookmark $model): bool
    {
        return true;
    }

    public function updateBulk(User $user, MapBookmark $model): bool
    {
        return true;
    }

    public function deleteBulk(User $user, MapBookmark $model): bool
    {
        return true;
    }

    public function delete(User $user, MapBookmark $model): bool
    {
        return true;
    }
}
