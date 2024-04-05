<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $keyType = 'string';

    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function lines(): hasMany
    {
        return $this->hasMany(InvoiceProductLine::class);
    }
}
