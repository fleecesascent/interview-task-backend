<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice as InvoiceEntity;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;

class EntityRejectedListener
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

    /**
     * Handle the event.
     *
     * @param  object  $event
     */
    public function handle(EntityRejected $event): void
    {
        if (InvoiceEntity::class !== $event->approvalDto->entity) {
            return;
        }

        $invoice = $this->invoiceRepository->getInvoice($event->approvalDto->id);

        if (null === $invoice) {
            return;
        }

        $invoice->setStatus(StatusEnum::REJECTED);
        $this->invoiceRepository->updateStatus($invoice);
    }
}
