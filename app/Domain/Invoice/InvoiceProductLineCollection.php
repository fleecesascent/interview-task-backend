<?php

declare(strict_types=1);

namespace App\Domain\Invoice;

class InvoiceProductLineCollection
{
    public function __construct(InvoiceProductLine ...$products)
    {
        $this->products = $products;
    }

    /** @return InvoiceProductLine[] */
    public function getProducts(): array
    {
        return $this->products;
    }
}
