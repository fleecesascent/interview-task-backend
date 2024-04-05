<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Domain\Invoice\InvoiceProductLine;

class InvoiceProductLineToArray
{
    /** @return mixed[] */
    public function map(InvoiceProductLine $product): array
    {
        return [
            'name' => $product->getProduct()->getName(),
            'quantity' => $product->getQuantity(),
            'unitPrice' => $product->getProduct()->getPrice(),
            'total' => $product->getTotal(),
        ];
    }
}
