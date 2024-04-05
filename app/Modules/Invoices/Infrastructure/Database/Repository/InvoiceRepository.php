<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Repository;

use App\Domain\Company as CompanyEntity;
use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice as InvoiceEntity;
use App\Domain\Invoice\InvoiceProductLine as InvoiceProductLineEntity;
use App\Domain\Invoice\InvoiceProductLineCollection;
use App\Domain\Product as ProductEntity;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Model\Company;
use App\Modules\Invoices\Infrastructure\Database\Model\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Model\InvoiceProductLine;
use App\Modules\Invoices\Infrastructure\Database\Model\Product;
use DateTime;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function getInvoices(int $offset, int $limit): Collection
    {
        return Invoice::with(['company', 'lines', 'lines.product'])
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(fn(Invoice $invoice) => $this->mapToInvoiceEntity($invoice));
    }

    public function getInvoice(UuidInterface $invoiceId): InvoiceEntity|null
    {
        $invoice = Invoice::find($invoiceId);

        return $invoice ? $this->mapToInvoiceEntity($invoice) : null;
    }

    public function updateStatus(InvoiceEntity $invoice): void
    {
        $model = Invoice::find($invoice->getId());
        $model->status = $invoice->getStatus()->value;
        $model->save();
    }

    private function mapToInvoiceEntity(Invoice $invoice): InvoiceEntity
    {
        return new InvoiceEntity(
            Uuid::fromString($invoice->id),
            StatusEnum::from($invoice->status),
            Uuid::fromString($invoice->number),
            DateTime::createFromFormat('Y-m-d', $invoice->date)->setTime(0, 0),
            DateTime::createFromFormat('Y-m-d', $invoice->due_date)->setTime(0, 0),
            $this->mapCompanyToEntity($invoice->company),
            $this->mapCompanyToEntity($invoice->company),
            $this->mapLines($invoice->lines),
        );
    }

    private function mapCompanyToEntity(Company $company): CompanyEntity
    {
        return new CompanyEntity(
            Uuid::fromString($company->id),
            $company->name,
            $company->street,
            $company->city,
            $company->zip,
            $company->phone,
            $company->email,
        );
    }

    private function mapLines(EloquentCollection $lines): InvoiceProductLineCollection
    {
        /** @var InvoiceProductLineEntity[] $products */
        $products = $lines->map(
            fn(InvoiceProductLine $line) => new InvoiceProductLineEntity(
                Uuid::fromString($line->id),
                $this->mapProductToEntity($line->product),
                $line->quantity,
                $line->quantity * $line->product->price,
            )
        )->toArray();

        return new InvoiceProductLineCollection(...$products);
    }

    private function mapProductToEntity(Product $product): ProductEntity
    {
        return new ProductEntity(
            Uuid::fromString($product->id),
            $product->name,
            $product->price,
            $product->currency,
        );
    }
}
