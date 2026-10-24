<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobQuotationItem extends Model
{
    protected $guarded = ['id'];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(JobQuotation::class, 'job_quotation_id');
    }

    public function profileProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'profile_product_id');
    }

    public function glassProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'glass_product_id');
    }
}
