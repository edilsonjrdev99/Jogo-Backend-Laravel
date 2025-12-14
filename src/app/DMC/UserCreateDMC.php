<?php

namespace App\DMC;

class UserCreateDMC
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly bool $is_admin = false,
    ) {}

    public static function fromArray(array $data): self {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            is_admin: $data['is_admin'] ?? false,
        );
    }

    public function toArray(): array {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
            'is_admin' => $this->is_admin,
        ];
    }
}
