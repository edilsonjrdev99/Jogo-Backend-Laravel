<?php

namespace App\DMC;

use App\Enums\UserClassEnum;

class UserCreateDMC
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly bool $is_admin = false,
        public readonly UserClassEnum $class = UserClassEnum::WARRIOR
    ) {}

    public static function fromArray(array $data): self {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            is_admin: $data['is_admin'] ?? false,
            class: isset($data['class']) ? UserClassEnum::from($data['class']) : UserClassEnum::WARRIOR
        );
    }

    public function toArray(): array {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
            'is_admin' => $this->is_admin,
            'class'    => $this->class->value
        ];
    }
}
