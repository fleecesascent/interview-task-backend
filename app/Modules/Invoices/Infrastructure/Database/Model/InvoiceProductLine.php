<?php

namespace App\Modules\Invoices\Infrastructure\Database\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProductLine extends Model
{
    protected $keyType = 'string';

    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
