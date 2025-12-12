<?php

namespace App\DMC;

class UserDeleteDMC
{
    public function __construct(
        public readonly int $userId,
    ) {}

    public static function fromId(int $userId): self {
        return new self(userId: $userId);
    }
}
