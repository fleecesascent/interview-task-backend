<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Enums\StatusEnum;
use App\Domain\Invoice\InvoiceProductLine;
use App\Domain\Invoice\InvoiceProductLineCollection;
use DateTime;
use Ramsey\Uuid\UuidInterface;

class Invoice
{
    public function __construct(
        private UuidInterface $id,
        private StatusEnum $status,
        private UuidInterface $number,
        private DateTime $date,
        private DateTime $dueDate,
        private Company $company,
        private Company $billedCompany,
        private InvoiceProductLineCollection $products,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getNumber(): UuidInterface
    {
        return $this->number;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getBilledCompany(): Company
    {
        return $this->billedCompany;
    }

    public function getProducts(): InvoiceProductLineCollection
    {
        return $this->products;
    }

    public function calculateTotal(): int
    {
        return array_sum(
            array_map(
                fn (InvoiceProductLine $product) => $product->getTotal(),
                $this->products->getProducts(),
            ),
        );
    }
}
