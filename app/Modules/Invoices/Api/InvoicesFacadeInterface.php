<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface InvoicesFacadeInterface
{
    public function getInvoices(int $offset, int $limit): Collection;
    public function approveInvoice(UuidInterface $invoiceId): void;
    public function rejectInvoice(UuidInterface $invoiceId): void;
}
