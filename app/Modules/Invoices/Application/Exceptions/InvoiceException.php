<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Exceptions;

use RuntimeException;

class InvoiceException extends RuntimeException
{
    public static function invoiceNotFound(): self
    {
        return new self('Invoice not found');
    }

    public static function invoiceStatusCantBeChanged(string $reason): self
    {
        return new self('Invoice status can\'t be changed: ' . $reason);
    }
}
