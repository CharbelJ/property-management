<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AnalyticType;
use App\Models\Property;
use App\Models\PropertyAnalytics;
use Tests\TestCase;

class PropertyAnalyticsControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        factory(Property::class)->create([
            'id' => 1,
            'suburb' => 'Parramatta',
            'state' => 'NSW',
            'country' => 'Australia',
        ]);

        factory(PropertyAnalytics::class)->create([
            'id' => 1,
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 1000,
        ]);

        factory(PropertyAnalytics::class)->create([
            'id' => 2,
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 2000,
        ]);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function addingOrUpdatingAnAnalyticToPropertyShouldReturn422()
    {
        $response = $this->postJson('/api/property-analytics');

        $response->assertStatus(422);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function addingOrUpdatingAnAnalyticToPropertyShouldReturn400IfAnalyticNotFound()
    {
        factory(AnalyticType::class)->create([
            'id' => 1,
        ]);

        $response = $this->postJson('/api/property-analytics', [
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 1000,
        ]);

        $response->assertStatus(400);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function shouldAddAnalyticToProperty()
    {
        $analyticType = factory(AnalyticType::class)->create([
            'id' => 1,
        ]);

        $this->assertDatabaseMissing('property_analytics', [
            'property_id' => 1,
            'analytic_type_id' => $analyticType->id,
            'value' => 1000,
        ]);

        $response = $this->postJson('/api/property-analytics', [
            'property_id' => 1,
            'analytic_type_id' => $analyticType->id,
            'value' => 1000,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('property_analytics', [
            'property_id' => 1,
            'analytic_type_id' => $analyticType->id,
            'value' => 1000,
        ]);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function shouldUpdateAnalyticToProperty()
    {
        $analyticType1 = factory(AnalyticType::class)->create([
            'id' => 1,
        ]);

        $analyticType2 = factory(AnalyticType::class)->create([
            'id' => 2,
        ]);

        factory(PropertyAnalytics::class)->create([
            'property_id' => 1,
            'analytic_type_id' => $analyticType1->id,
            'value' => 1000,
        ]);

        $response = $this->postJson('/api/property-analytics', [
            'property_id' => 1,
            'analytic_type_id' => 2,
            'value' => 1000,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('property_analytics', [
            'property_id' => 1,
            'analytic_type_id' => $analyticType2->id,
            'value' => 1000,
        ]);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function shouldGetAllAnalyticsForAnInputtedProperty()
    {
        $analyticType1 = factory(AnalyticType::class)->create([
            'id' => 1,
            'name' => 'max_height',
            'units' => 'mm',
            'is_numeric' => 1,
            'num_decimal_places' => 0,
        ]);

        $analyticType2 = factory(AnalyticType::class)->create([
            'id' => 2,
            'name' => 'min_height',
            'units' => 'mm',
            'is_numeric' => 1,
            'num_decimal_places' => 0,
        ]);

        factory(PropertyAnalytics::class)->create([
            'id' => 4,
            'property_id' => 7,
            'analytic_type_id' => $analyticType1->id,
            'value' => 1000,
        ]);

        factory(PropertyAnalytics::class)->create([
            'id' => 5,
            'property_id' => 7,
            'analytic_type_id' => $analyticType2->id,
            'value' => 500,
        ]);

        $response = $this->getJson('/api/property-analytics/7');

        $response->assertJson([
            [
                'id' => 4,
                'property_id' => 7,
                'analytic_type_id' => [
                    'id' => $analyticType1->id,
                    'name' => 'max_height',
                    'units' => 'mm',
                    'is_numeric' => 1,
                    'num_decimal_places' => 0,
                ],
                'value' => '1000',
            ],
            [
                'id' => 5,
                'property_id' => 7,
                'analytic_type_id' => [
                    'id' => $analyticType2->id,
                    'name' => 'min_height',
                    'units' => 'mm',
                    'is_numeric' => 1,
                    'num_decimal_places' => 0,
                ],
                'value' => '500',
            ],
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldReturn400WhenGettingASummaryOfAllPropertyAnalyticsForAnInputtedSuburbIfSuburbNotFound()
    {
        $response = $this->getJson('/api/property-analytics/suburb/Parra');

        $response->assertStatus(400);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldGetASummaryOfAllPropertyAnalyticsForAnInputtedSuburb()
    {
        $response = $this->getJson('/api/property-analytics/suburb/Parramatta');

        $response->assertJson([
            'location_type' => 'Suburb',
            'location_name' => 'Parramatta',
            'summary' => [
                'min_value' => '1000',
                'max_value' => '2000',
                'median' => 1500,
                'percentage_properties_with_value' => 100,
                'percentage_properties_with_no_value' => 0,
            ]
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldReturn400WhenGettingASummaryOfAllPropertyAnalyticsForAnInputtedStateIfStateNotFound()
    {
        $response = $this->getJson('/api/property-analytics/state/NS');

        $response->assertStatus(400);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldGetASummaryOfAllPropertyAnalyticsForAnInputtedState()
    {
        $response = $this->getJson('/api/property-analytics/state/NSW');

        $response->assertJson([
            'location_type' => 'State',
            'location_name' => 'NSW',
            'summary' => [
                'min_value' => '1000',
                'max_value' => '2000',
                'median' => 1500,
                'percentage_properties_with_value' => 100,
                'percentage_properties_with_no_value' => 0,
            ]
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldReturn400WhenGettingASummaryOfAllPropertyAnalyticsForAnInputtedCountryIfCountryNotFound()
    {
        $response = $this->getJson('/api/property-analytics/country/Au');

        $response->assertStatus(400);
    }

    /**
     * @test
     * @group PropertyAnalyticsController
     */
    public function ShouldGetASummaryOfAllPropertyAnalyticsForAnInputtedCountry()
    {
        $response = $this->getJson('/api/property-analytics/country/Australia');

        $response->assertJson([
            'location_type' => 'Country',
            'location_name' => 'Australia',
            'summary' => [
                'min_value' => '1000',
                'max_value' => '2000',
                'median' => 1500,
                'percentage_properties_with_value' => 100,
                'percentage_properties_with_no_value' => 0,
            ]
        ]);

        $response->assertStatus(200);
    }
}
