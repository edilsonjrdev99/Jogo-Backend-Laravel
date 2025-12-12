<?php

namespace App\DMC\Auth;

class AuthUserLoginDMC {
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self {
        return new self(
            $data['email'],
            $data['password']
        );
    }

    public function toArray(): array {
        return [
            'email'    => $this->email,
            'password' => $this->password
        ];
    }
}