<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

class CompanyValues
{
    public function __construct(
        private string $name,
        private string $address,
        private string $city,
        private string $zipCode,
        private string $phone,
    ) {
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

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zipCode' => $this->zipCode,
            'phone' => $this->phone,
        ];
    }
}
