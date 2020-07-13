<?php

namespace App\Handlers;

use App\Models\PropertyAnalytics;

class PropertyAnalyticsHandler
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Build summary for property analytics
     *
     * @param string $locationType
     * @param string $locationName
     * @param array $propertyIds
     * @return array
     */
    public function buildSummary(string $locationType, string $locationName, $propertyIds)
    {
        $this->getProperties($propertyIds);

        $this->data['location_type'] = $locationType;
        $this->data['location_name'] = $locationName;

        $this->data['summary'] = [
            'min_value' => $this->getMin($propertyIds),
            'max_value' => $this->getMax($propertyIds),
            'median' => $this->getMedian($propertyIds),
            'percentage_properties_with_value' => $this->getPercentagePropertiesWithValue($propertyIds),
            'percentage_properties_with_no_value' => $this->getPercentagePropertiesWithNoValue($propertyIds),
        ];

        return $this->data;
    }

    /**
     * Get ids of properties
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getProperties($propertyIds)
    {
        return PropertyAnalytics::whereIn('property_id', $propertyIds)->get();
    }

    /**
     * Get min value
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getMin($propertyIds)
    {
        return $this->getProperties($propertyIds)->min('value');
    }

    /**
     * Get max value
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getMax($propertyIds)
    {
        return $this->getProperties($propertyIds)->max('value');
    }

    /**
     * Get median
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getMedian($propertyIds)
    {
        return $this->getProperties($propertyIds)->median('value');
    }

    /**
     * Get total properties with a value
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getTotalPropertiesWithValue($propertyIds)
    {
        return $this->getProperties($propertyIds)
            ->where('value', '>', 0)
            ->count();
    }

    /**
     * Get total properties with no value
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getTotalPropertiesWithNoValue($propertyIds)
    {
        return $this->getProperties($propertyIds)
            ->where('value', 0)
            ->count();
    }

    /**
     * Get total properties
     *
     * @param array $propertyIds
     * @return mixed
     */
    protected function getTotalProperties($propertyIds)
    {
        return $this->getProperties($propertyIds)
            ->count();
    }

    /**
     * Get percentage of properties with a value
     *
     * @param array $propertyIds
     * @return float|int
     */
    protected function getPercentagePropertiesWithValue($propertyIds)
    {
        return $this->getTotalPropertiesWithValue($propertyIds) / $this->getTotalProperties($propertyIds) * 100;
    }

    /**
     * Get percentage of properties with no value
     *
     * @param array $propertyIds
     * @return float|int
     */
    protected function getPercentagePropertiesWithNoValue($propertyIds)
    {
        return $this->getTotalPropertiesWithNoValue($propertyIds) / $this->getTotalProperties($propertyIds) * 100;
    }
}
