<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

class ProductValues
{
    public function __construct(
        private string $name,
        private int $quantity,
        private int $unitPrice,
        private float $total,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'total' => $this->total,
        ];
    }
}
