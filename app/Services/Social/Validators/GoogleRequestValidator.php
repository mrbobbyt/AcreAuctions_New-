<?php
declare(strict_types = 1);

namespace App\Services\Social\Validators;

use Google_Service_Oauth2_Userinfoplus;
use Illuminate\Validation\ValidationException;
use Validator;

class GoogleRequestValidator
{

    /**
     * Return validated array of data
     * @param Google_Service_Oauth2_Userinfoplus $data
     * @return array
     * @throws ValidationException
     */
    public function attempt(Google_Service_Oauth2_Userinfoplus $data)
    {
        $user = [
            'email' => $data->getEmail(),
            'fname' => $data->getGivenName(),
            'lname' => $data->getFamilyName(),
        ];

        return [
            'body' => $this->validateBody($user)
        ];
    }

    /**
     * Validate given data
     * @param $user
     * @throws ValidationException
     * @return array
     */
    public function validateBody($user)
    {
        $validator = Validator::make($user, [
            'email' => 'required|string|email|max:255',
            'fname' => 'required|string|max:255|min:3',
            'lname' => 'required|string|max:255|min:3',
        ]);

        return $validator->validate();
    }

}
