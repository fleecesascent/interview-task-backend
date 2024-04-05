<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

class InvoiceValues
{
    public function __construct(
        private string $id,
        private string $number,
        private string $date,
        private string $dueDate,
        private CompanyValues $company,
        private CompanyValues $billedCompany,
        private ProductValuesCollection $products,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDueDate(): string
    {
        return $this->dueDate;
    }

    public function getCompany(): CompanyValues
    {
        return $this->company;
    }

    public function getBilledCompany(): CompanyValues
    {
        return $this->billedCompany;
    }

    public function getProducts(): ProductValuesCollection
    {
        return $this->products;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'date' => $this->date,
            'dueDate' => $this->dueDate,
            'company' => $this->company->toArray(),
            'billedCompany' => $this->billedCompany->toArray(),
            'products' => array_map(
                fn (ProductValues $product) => $product->toArray(),
                $this->products->toArray()
            ),
        ];
    }
}
