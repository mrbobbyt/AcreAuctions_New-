<?php
declare(strict_types = 1);

namespace App\Services\Seller\Validators;

use App\Services\Auth\Validators\AbstractValidator;
use Illuminate\Http\Request;
use Validator;

class CreateSellerRequestValidator implements AbstractValidator
{
    /**
     * Return validated array of data
     * @param Request $request
     * @return array
     */
    public function attempt(Request $request)
    {
        return $this->validateBody($request);
    }


    /**
     * Validate given data
     * @param Request $request
     * @return array
     */
    public function validateBody(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:users',
            'clientUrl' => 'required|string',
            'f_name' => 'required|string|max:255|min:3',
            'l_name' => 'required|string|max:255|min:3',
            'mail_address' => 'required|string|max:255',
            'phone_number' => 'required|numeric',
        ]);

        return $validator->validate();
    }
}
