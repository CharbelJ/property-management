<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class AnalyticType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analytic_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'units',
        'is_numeric',
        'num_decimal_places',
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
     * An analytic type has many property analytics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function propertyAnalytics()
    {
        return $this->hasMany(PropertyAnalytics::class);
    }
}
