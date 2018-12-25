<?php
declare(strict_types = 1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryContract;
use Exception;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryContract
{

    /**
     * Find user using id
     *
     * @param int $id
     * @throws Exception
     * @return Model
     */
    public function findByPk(int $id): Model
    {
        if ($user = User::query()->find($id)) {
            return $user;
        }

        throw new Exception('User not exist.');
    }


    /**
     * Find user using email
     *
     * @param string $email
     * @throws Exception
     * @return User
     */
    public function findByEmail(string $email): User
    {
        if ($user = User::query()->where('email', $email)->first()) {
            return $user;
        }

        throw new Exception('User not exist.');
    }


    /**
     * Check if user exists in db
     *
     * @param string $email
     * @return bool
     */
    public function checkUserExists(string $email): bool
    {
        return User::query()->where('email', $email)->exists();
    }


    /**
     * Check if user is admin
     *
     * @param int $id
     * @return bool
     */
    public function isAdmin(int $id): bool
    {
        return User::query()->where([ ['id', $id], ['role', 1] ])->exists();
    }
}
