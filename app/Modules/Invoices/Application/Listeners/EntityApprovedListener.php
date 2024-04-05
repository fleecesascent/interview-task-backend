<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice as InvoiceEntity;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;

class EntityApprovedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function handle(EntityApproved $event): void
    {
        if (InvoiceEntity::class !== $event->approvalDto->entity) {
            return;
        }

        $invoice = $this->invoiceRepository->getInvoice($event->approvalDto->id);

        if (null === $invoice) {
            return;
        }

        $invoice->setStatus(StatusEnum::APPROVED);
        $this->invoiceRepository->updateStatus($invoice);
    }
}
