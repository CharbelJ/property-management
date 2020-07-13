<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class PropertyAnalytics extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_analytics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'analytic_type_id',
        'value',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get the property that owns the property analytics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the analytic_type that owns the property analytics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function analyticType()
    {
        return $this->belongsTo(AnalyticType::class);
    }

    /**
     * Get the analytic type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function analyticTypeId()
    {
        return $this->belongsTo(AnalyticType::class, 'analytic_type_id');
    }
}
