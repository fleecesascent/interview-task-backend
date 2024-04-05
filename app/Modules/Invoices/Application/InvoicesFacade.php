<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application;

use App\Domain\Invoice;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Api\InvoicesFacadeInterface;
use App\Modules\Invoices\Application\Exceptions\InvoiceException;
use Illuminate\Support\Collection;
use LogicException;
use Ramsey\Uuid\UuidInterface;

class InvoicesFacade implements InvoicesFacadeInterface
{
    private array $operationLogs = [];

    public function __construct(
        private InvoiceRepositoryInterface $invoicesRepository,
        private ApprovalFacadeInterface $approvalFacade,
    ) {
    }

    public function getInvoices(int $offset, int $limit): Collection
    {
        return $this->invoicesRepository->getInvoices($offset, $limit);
    }

    public function approveInvoice(UuidInterface $invoiceId): void
    {
        $invoice = $this->getInvoice($invoiceId);

        try {
            $this->approvalFacade->approve(
                new ApprovalDto(
                    $invoice->getId(),
                    $invoice->getStatus(),
                    Invoice::class,
                )
            );
        } catch (LogicException $exception) {
            throw InvoiceException::invoiceStatusCantBeChanged($exception->getMessage());
        }
    }

    public function rejectInvoice(UuidInterface $invoiceId): void
    {
        $invoice = $this->getInvoice($invoiceId);

        try {
            $this->approvalFacade->reject(
                new ApprovalDto(
                    $invoice->getId(),
                    $invoice->getStatus(),
                    Invoice::class,
                )
            );
        } catch (LogicException $exception) {
            throw InvoiceException::invoiceStatusCantBeChanged($exception->getMessage());
        }
    }

    private function getInvoice(UuidInterface $invoiceId): Invoice
    {
        $invoice = $this->invoicesRepository->getInvoice($invoiceId);

        if (null === $invoice) {
            throw InvoiceException::invoiceNotFound();
        }

        return $invoice;
    }
}
