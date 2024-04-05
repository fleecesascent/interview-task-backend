<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Domain\Company;

class CompanyToArray
{
    /** @return string[] */
    public function map(Company $company): array
    {
        return [
            'name' => $company->getName(),
            'streetAddress' => $company->getAddress(),
            'city' => $company->getCity(),
            'zipCode' => $company->getZipCode(),
            'phone' => $company->getPhone(),
            'email' => $company->getEmail(),
        ];
    }
}
