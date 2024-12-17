<?php

namespace App\Service\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Password\PasswordGenerator;

class UserFactory
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function create(string $username, ?string $otp = null): User
    {
        // TODO
        // $hash = $this->otpHasher->hash($otp ?? PasswordGenerator::generate(), "zebi");

        $new = (new User())
            ->setUsername($username)
            ->setOtp($otp ?? PasswordGenerator::generate());

        $this->userRepository->save($new);

        return $new;
    }
}
