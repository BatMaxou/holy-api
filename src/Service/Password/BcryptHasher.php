<?php

namespace App\Service\Password;

class BcryptHasher
{
    public function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, PASSWORD_BCRYPT);
    }
}
