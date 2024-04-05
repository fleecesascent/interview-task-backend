<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;
use App\Modules\Invoices\Application\Listeners\EntityApprovedListener;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class EntityApprovedListenerTest extends TestCase
{
    private EntityApprovedListener $entityApprovedListener;
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
        $this->entityApprovedListener = new EntityApprovedListener($this->invoiceRepository);
    }

    public function testHandle(): void
    {
        $this->invoiceRepository->expects($this->once())
            ->method('getInvoice')
            ->willReturn($this->invoice);
        $this->invoice->expects($this->once())
            ->method('setStatus')
            ->with(StatusEnum::APPROVED);
        $this->invoiceRepository->expects($this->once())
            ->method('updateStatus')
            ->with($this->invoice);

        $this->entityApprovedListener->handle(
            new EntityApproved(
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

        $this->entityApprovedListener->handle(
            new EntityApproved(
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

        $this->entityApprovedListener->handle(
            new EntityApproved(
                new ApprovalDto(
                    $this->uuid,
                    StatusEnum::DRAFT,
                    Invoice::class,
                )
            )
        );
    }
}
