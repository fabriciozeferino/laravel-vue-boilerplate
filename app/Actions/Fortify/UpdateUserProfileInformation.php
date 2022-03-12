<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            /** @var User $user */
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill(
                [
                    'name' => $input['name'],
                    'email' => $input['email'],
                ]
            )->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     * @param \App\Models\User $user
     * @param array $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill(
            [
                'name' => $input['name'],
                'email' => $input['email'],
                'email_verified_at' => null,
            ]
        )->save();

        $user->sendEmailVerificationNotification();
    }
}
