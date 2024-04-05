<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;
use App\Modules\Invoices\Application\Listeners\EntityRejectedListener;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class EntityRejectedListenerTest extends TestCase
{
    private EntityRejectedListener $entityRejectedListener;
    private InvoiceRepositoryInterface $invoiceRepository;
    private UuidInterface $uuid;
    private Invoice $invoice;

    public function setUp(): void
    {
        $this->uuid = $this->createMock(UuidInterface::class);
        $this->invoice = $this->createMock(Invoice::class);
        $this->invoice->method('getId')
            ->willReturn($this->uuid);

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $this->entityRejectedListener = new EntityRejectedListener($this->invoiceRepository);
    }

    public function testHandle(): void
    {
        $this->invoiceRepository->expects($this->once())
            ->method('getInvoice')
            ->willReturn($this->invoice);
        $this->invoice->expects($this->once())
            ->method('setStatus')
            ->with(StatusEnum::REJECTED);
        $this->invoiceRepository->expects($this->once())
            ->method('updateStatus')
            ->with($this->invoice);

        $this->entityRejectedListener->handle(
            new EntityRejected(
                new ApprovalDto(
                    $this->uuid,
                    StatusEnum::DRAFT,
                    Invoice::class,
                )
            )
        );
    }

    public function testHandleWithDifferentEntity(): void
    {
        $this->invoiceRepository->expects($this->never())
            ->method('getInvoice');
        $this->invoice->expects($this->never())
            ->method('setStatus');
        $this->invoiceRepository->expects($this->never())
            ->method('updateStatus');

        $this->entityRejectedListener->handle(
            new EntityRejected(
                new ApprovalDto(
                    $this->uuid,
                    StatusEnum::DRAFT,
                    'DifferentEntity',
                )
            )
        );
    }

    public function testHandleWithNullInvoice(): void
    {
        $this->invoiceRepository->expects($this->once())
            ->method('getInvoice')
            ->willReturn(null);
        $this->invoice->expects($this->never())
            ->method('setStatus');
        $this->invoiceRepository->expects($this->never())
            ->method('updateStatus');

        $this->entityRejectedListener->handle(
            new EntityRejected(
                new ApprovalDto(
                    $this->uuid,
                    StatusEnum::DRAFT,
                    Invoice::class,
                )
            )
        );
    }
}
