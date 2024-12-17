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

    public function create(string $username): string
    {
        $otp = PasswordGenerator::generate();

        $new = (new User())
            ->setUsername($username)
            ->setOtp($otp);

        $this->userRepository->save($new);

        return $otp;
    }
}
