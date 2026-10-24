<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractorCommission extends Model
{
    protected $guarded = ['id'];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'contractor_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
