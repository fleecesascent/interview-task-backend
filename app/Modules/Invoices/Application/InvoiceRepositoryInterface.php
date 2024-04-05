<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application;

use App\Domain\Invoice as InvoiceEntity;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{
    public function getInvoices(int $offset, int $limit): Collection;
    public function getInvoice(UuidInterface $invoiceId): InvoiceEntity|null;
    public function updateStatus(InvoiceEntity $invoice): void;
}
