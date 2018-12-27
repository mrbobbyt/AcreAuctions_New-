<?php
declare(strict_types = 1);

namespace App\Services\User\Contracts;

use Exception;
use Throwable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\JWTException;

interface UserServiceContract
{

    /**
     * Return authenticate user
     *
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function authenticate();


    /**
     * Update user
     *
     * @param array $data
     * @return false|JWTSubject
     * @throws JWTException
     */
    public function update(array $data);


    /**
     * Return id auth user
     *
     * @return int
     */
    public function getID();


    /**
     * Delete auth user
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id);


    /**
     * Create User avatar
     * @param array $data
     * @param int $id
     * @return bool
     * @throws Throwable
     * @throws Exception
     */
    public function updateAvatar(array $data, int $id);
}
