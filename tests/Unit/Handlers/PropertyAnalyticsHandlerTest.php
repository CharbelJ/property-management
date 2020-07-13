<?php

namespace Tests\Unit\Handlers;

use App\Handlers\PropertyAnalyticsHandler;
use App\Models\PropertyAnalytics;
use Tests\TestCase;

class PropertyAnalyticsHandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 500,
        ]);

        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 2000,
        ]);

        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 1000,
        ]);

        $this->propertyAnalyticsHandler = app(PropertyAnalyticsHandler::class);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldBuildPropertyAnalyticsSummary()
    {
        $actualSummary = $this->propertyAnalyticsHandler->buildSummary('Suburb', 'Parramatta', [1]);

        $expectedSummary = [
            'location_type' => 'Suburb',
            'location_name' => 'Parramatta',
            'summary' => [
                'min_value' => '500',
                'max_value' => '2000',
                'median' => '1000',
                'percentage_properties_with_value' => 100,
                'percentage_properties_with_no_value' => 0,
            ]
        ];

        $this->assertEquals($expectedSummary, $actualSummary);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetMinValueOfProperties()
    {
        $getMinvalue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getMin', [[1]]);

        $this->assertEquals(500, $getMinvalue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetMaxValueOfProperties()
    {
        $getMinvalue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getMax', [[1]]);

        $this->assertEquals(2000, $getMinvalue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetMedianValueOfProperties()
    {
        $getMedianvalue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getMedian', [[1]]);

        $this->assertEquals(1000, $getMedianvalue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetTotalPropertiesWithValue()
    {
        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => '',
        ]);

        $getTotalPropertiesWithValue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getTotalPropertiesWithValue', [[1]]);

        $this->assertEquals(3, $getTotalPropertiesWithValue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetTotalPropertiesWithNoValue()
    {
        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => '',
        ]);

        $getTotalPropertiesWithNoValue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getTotalPropertiesWithNoValue', [[1]]);

        $this->assertEquals(1, $getTotalPropertiesWithNoValue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetTotalProperties()
    {
        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => '',
        ]);

        $getTotalProperties = $this->invokeMethod($this->propertyAnalyticsHandler, 'getTotalProperties', [[1]]);

        $this->assertEquals(4, $getTotalProperties);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetPercentagePropertiesWithValue()
    {
        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => '',
        ]);

        $getPercentagePropertiesWithValue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getPercentagePropertiesWithValue', [[1]]);

        $this->assertEquals(75, $getPercentagePropertiesWithValue);
    }

    /**
     * @test
     * @group PropertyAnalyticsHandler
     */
    public function shouldGetPercentagePropertiesWithNoValue()
    {
        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => '',
        ]);

        $getPercentagePropertiesWithNoValue = $this->invokeMethod($this->propertyAnalyticsHandler, 'getPercentagePropertiesWithNoValue', [[1]]);

        $this->assertEquals(25, $getPercentagePropertiesWithNoValue);
    }
}
