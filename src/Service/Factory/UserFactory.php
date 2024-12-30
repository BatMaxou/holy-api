<?php

namespace App\Service\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Password\BcryptHasher;
use App\Service\Password\PasswordGenerator;

class UserFactory
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BcryptHasher $bcryptHasher,
    ) {
    }

    public function create(string $username, ?string $otp = null): User
    {
        $hash = $this->bcryptHasher->hash($otp ?? PasswordGenerator::generate());

        $new = (new User())
            ->setUsername($username)
            ->setOtp($hash);

        $this->userRepository->save($new);

        return $new;
    }
}
