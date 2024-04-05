<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Domain\Invoice;
use App\Domain\Invoice\InvoiceProductLine;

class InvoiceToArray
{
    public function __construct(
        private CompanyToArray $companyToArray,
        private InvoiceProductLineToArray $invoiceProductLineToArray
    ) {
    }

    /** @return mixed[] */
    public function map(Invoice $invoice): array
    {
        return [
            'invoiceNumber' => $invoice->getNumber()->toString(),
            'invoiceDate' => $invoice->getDate()->format('Y-m-d'),
            'dueDate' => $invoice->getDueDate()->format('Y-m-d'),
            'company' => $this->companyToArray->map($invoice->getCompany()),
            'billedCompany' => '',
            'products' => array_map(
                fn(InvoiceProductLine $product) => $this->invoiceProductLineToArray->map($product),
                $invoice->getProducts()->getProducts(),
            ),
            'totalPrice' => $invoice->calculateTotal(),
        ];
    }
}
