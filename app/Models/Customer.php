<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $guarded = ['id'];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(MoneyReceipt::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(ContractorCommission::class, 'contractor_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(JobQuotation::class);
    }
}
