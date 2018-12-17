<?php

namespace App\Services\User\Contracts;


use Exception;
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
}
