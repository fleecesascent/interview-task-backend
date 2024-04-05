<?php

declare(strict_types=1);

namespace App\Domain\Invoice;

use App\Domain\Product;
use Ramsey\Uuid\UuidInterface;

class InvoiceProductLine
{
    public function __construct(
        private UuidInterface $id,
        private Product $product,
        private int $quantity,
        private int $total,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
