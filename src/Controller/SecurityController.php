<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Route('/otp/verify', name: 'verify_otp', methods: ['POST'])]
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), false);
        if (!is_object($data)) {
            throw new \Exception('Invalid data');
        }

        if (!isset($data->username) || !is_string($data->username)) {
            throw new \Exception('Invalid username');
        }

        if (!isset($data->otp) || !is_string($data->otp)) {
            throw new \Exception('Invalid OTP');
        }

        $username = $data->username;
        $otp = $data->otp;

        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user) {
            // TODO
            // if ($this->otpHasher->verify($user->getOtp(), $otp)) {
            if ($user->getOtp() === $otp) {
                return new JsonResponse(['message' => 'OK']);
            }
        }

        return new JsonResponse(['message' => 'Unauthorized'], 401);
    }

    #[Route('/create-password', name: 'create_password', methods: ['POST'])]
    public function createPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), false);
        if (!is_object($data)) {
            throw new \Exception('Invalid data');
        }

        if (!isset($data->username) || !is_string($data->username)) {
            throw new \Exception('Invalid username');
        }

        if (!isset($data->otp) || !is_string($data->otp)) {
            throw new \Exception('Invalid OTP');
        }

        if (!isset($data->password) || !is_string($data->password)) {
            throw new \Exception('Invalid password');
        }

        $username = $data->username;
        $otp = $data->otp;
        $password = $data->password;

        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user && $user->getOtp() === $otp) {
            $user->setOtp(null);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $this->userRepository->save($user);

            return new JsonResponse(['id' => $user->getId()]);
        }

        return new JsonResponse(['message' => 'Bad Request'], 400);
    }
}
