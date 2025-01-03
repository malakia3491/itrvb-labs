<?php

namespace Lab3\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;
use Exception;
use DateTimeImmutable;

use Lab3\Repositories\UsersRepository;

class AuthController {
    private UsersRepository $usersRepository;
    private LoggerInterface $logger;
    private string $jwtSecret;

    public function __construct(UsersRepository $usersRepository, LoggerInterface $logger, string $jwtSecret)
    {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
        $this->jwtSecret = $jwtSecret;
    }

    public function login(string $username, string $password): ?string
    {
        // Проверка учетных данных пользователя
        $user = $this->usersRepository->findByUsername($username);
        if (!$user || !$this->verifyPassword($password, $password)) {
            $this->logger->warning('Неудачная попытка входа для пользователя: ' . $username);
            return null;
        }

        // Генерация JWT
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1 hour')->getTimestamp();
        $payload = [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expire,
            'sub' => $user->getUuid(),
        ];

        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

        // Сохранение токена в базе данных
        $this->usersRepository->saveToken($user->getUuid(), $jwt, $expire);
        $this->logger->info('Пользователь вошел в систему: ' . $username);

        return $jwt;
    }

    public function logout(string $token): void
    {
        // Инвалидация токена
        $this->usersRepository->invalidateToken($token);
        $this->logger->info('Пользователь вышел из системы');
    }

    public function authenticate(): ?string
    {
        $token = $this->getTokenFromRequest();
        if (!$token) {
            $this->logger->warning('Токен не предоставлен');
            return null;
        }

        $userUuid = $this->validateToken($token);
        if (!$userUuid) {
            $this->logger->warning('Недействительный токен');
            return null;
        }

        return $userUuid;
    }

    public function getTokenFromRequest(): ?string
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return null;
        }
        return str_replace('Bearer ', '', $headers['Authorization']);
    }

    private function validateToken(string $token): ?string
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $currentTime = (new DateTimeImmutable())->getTimestamp();

            if ($decoded->exp < $currentTime) {
                $this->logger->warning('Токен истек');
                return null;
            }
            $result = $this->usersRepository->getToken($token);
            // Проверка наличия токена в базе данных
            if (!$result) {
                $this->logger->warning('Токен не найден в базе данных');
                return null;
            }

            return $decoded->sub;
        } catch (Exception $e) {
            $this->logger->error('Ошибка при валидации токена: ' . $e->getMessage());
            return null;
        }
    }

    private function verifyPassword(string $password, string $passwordHash): bool
    {
        return true;
    }
}
