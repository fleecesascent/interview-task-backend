<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Invoices\Api\InvoicesFacadeInterface;
use App\Modules\Invoices\Application\Exceptions\InvoiceException;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;
use App\Modules\Invoices\Application\InvoicesFacade;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class InvoicesFacadeTest extends TestCase
{
    private InvoicesFacadeInterface $invoicesFacade;
    private InvoiceRepositoryInterface $invoiceRepository;
    private ApprovalFacadeInterface $approvalFacade;
    private Invoice $invoice;
    private UuidInterface $invoiceId;

    public function setUp(): void
    {
        $this->invoiceId = $this->createMock(UuidInterface::class);
        $this->invoice = $this->createMock(Invoice::class);
        $this->invoice
            ->method('getId')
            ->willReturn($this->invoiceId);

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $this->approvalFacade = $this->createMock(ApprovalFacadeInterface::class);

        $this->invoicesFacade = new InvoicesFacade(
            $this->invoiceRepository,
            $this->approvalFacade,
        );
    }

    public function testGetInvoices(): void
    {
        $offset = 0;
        $limit = 10;

        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoices')
            ->with($offset, $limit);

        $this->invoicesFacade->getInvoices($offset, $limit);
    }

    public function testApproveInvoice(): void
    {
        $this->invoice
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn($this->invoice);
        $this->approvalFacade
            ->expects($this->once())
            ->method('approve');

        $this->invoicesFacade->approveInvoice($this->invoiceId);
    }

    public function testApproveInvoiceWhenInvoiceNotFound(): void
    {
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn(null);

        $this->expectException(InvoiceException::class);

        $this->invoicesFacade->approveInvoice($this->invoiceId);
    }

    /** @dataProvider statusDataProvider */
    public function testApproveInvoiceWhenInvoiceStatusAlreadyChanged(StatusEnum $currentStatus): void
    {
        $this->invoice
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn($currentStatus);
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn($this->invoice);
        $this->approvalFacade
            ->expects($this->once())
            ->method('approve')
            ->willThrowException(new \LogicException('approval status is already assigned'));

        $this->expectException(InvoiceException::class);

        $this->invoicesFacade->approveInvoice($this->invoiceId);
    }

    public function testRejectInvoice(): void
    {
        $this->invoice
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn($this->invoice);
        $this->approvalFacade
            ->expects($this->once())
            ->method('reject');

        $this->invoicesFacade->rejectInvoice($this->invoiceId);
    }

    public function testRejectInvoiceWhenInvoiceNotFound(): void
    {
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn(null);

        $this->expectException(InvoiceException::class);

        $this->invoicesFacade->rejectInvoice($this->invoiceId);
    }

    /** @dataProvider statusDataProvider */
    public function testRejectInvoiceWhenInvoiceStatusAlreadyChanged(StatusEnum $currentStatus): void
    {
        $this->invoice
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn($currentStatus);
        $this->invoiceRepository
            ->expects($this->once())
            ->method('getInvoice')
            ->with($this->invoiceId)
            ->willReturn($this->invoice);
        $this->approvalFacade
            ->expects($this->once())
            ->method('reject')
            ->willThrowException(new \LogicException('approval status is already assigned'));

        $this->expectException(InvoiceException::class);

        $this->invoicesFacade->rejectInvoice($this->invoiceId);
    }

    /** @return mixed[] */
    public static function statusDataProvider(): array
    {
        return [
            [StatusEnum::APPROVED],
            [StatusEnum::REJECTED],
        ];
    }
}
