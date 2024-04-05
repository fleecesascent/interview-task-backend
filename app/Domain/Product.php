<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\UuidInterface;

class Product
{
    public function __construct(
        private UuidInterface $id,
        private string $name,
        private int $price,
        private string $currency,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCurrency(): int
    {
        return $this->currency;
    }
}
