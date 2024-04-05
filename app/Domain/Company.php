<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\UuidInterface;

class Company
{
    public function __construct(
        private UuidInterface $id,
        private string $name,
        private string $address,
        private string $city,
        private string $zipCode,
        private string $phone,
        private string $email,
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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
