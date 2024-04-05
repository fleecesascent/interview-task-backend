<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

class ProductValuesCollection
{
    private array $products = [];

    public function __construct(ProductValues ...$products)
    {
        $this->products = $products;
    }

    /**
     * @return ProductValues[]
     */
    public function toArray(): array
    {
        return $this->products;
    }
}
