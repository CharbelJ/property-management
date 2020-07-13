<?php

namespace App\Http\Controllers;

use App\Handlers\PropertyAnalyticsHandler;
use App\Models\AnalyticType;
use App\Models\Property;
use App\Models\PropertyAnalytics;
use Illuminate\Http\Request;

class PropertyAnalyticsController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PropertyAnalyticsHandler
     */
    protected $propertyAnalyticsHandler;

    /**
     * PropertyAnalyticsController constructor.
     *
     * @param Request $request
     * @param PropertyAnalyticsHandler $propertyAnalyticsHandler
     */
    public function __construct(Request $request, PropertyAnalyticsHandler $propertyAnalyticsHandler)
    {
        $this->request = $request;
        $this->propertyAnalyticsHandler = $propertyAnalyticsHandler;
    }

    /**
     * POST - /property-analytics
     * Add\Update an analytic to a property
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate($this->request, [
            'property_id' => 'required|integer',
            'analytic_type_id' => 'required|integer',
            'value' => 'required|integer',
        ]);

        $analyticType = AnalyticType::find($this->request['analytic_type_id']);

        if (!$analyticType) {
            return response()->json(['errorMsg' => 'Analytic type not found'], 400);
        }

        PropertyAnalytics::updateOrCreate(
            ['property_id' => $this->request['property_id']],
            ['analytic_type_id' => $this->request['analytic_type_id'], 'value' => $this->request['value']]
        );

        return response()->json(['data' => 'Added\Updated an analytic to a property successfully'], 200);
    }

    /**
     * GET - /property-analytics/{propertyId}
     * Get all analytics for an inputted property
     *
     * @param int $propertyId
     * @return mixed
     */
    public function show(int $propertyId)
    {
        return PropertyAnalytics::where('property_id', $propertyId)
            ->with('analyticTypeId')
            ->get();
    }

    /**
     * GET - /property-analytics/suburb/{suburb}
     * Get a summary of all property analytics for an inputted suburb
     *
     * @param string $suburb
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getSuburb(string $suburb)
    {
        $findSuburb = Property::where('suburb', $this->request['suburb'])->exists();

        if (!$findSuburb) {
            return response()->json(['errorMsg' => 'Suburb not found'], 400);
        }

        $propertyIds = Property::where('suburb', $suburb)->pluck('id');

        return $this->propertyAnalyticsHandler->buildSummary('Suburb', $suburb, $propertyIds);
    }

    /**
     * GET - /property-analytics/state/{state}
     * Get a summary of all property analytics for an inputted state
     *
     * @param string $state
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getState(string $state)
    {
        $findState = Property::where('state', $state)->exists();

        if (!$findState) {
            return response()->json(['errorMsg' => 'State not found'], 400);
        }

        $propertyIds = Property::where('state', $state)->pluck('id');

        return $this->propertyAnalyticsHandler->buildSummary('State', $state, $propertyIds);
    }

    /**
     * GET - /property-analytics/country/{country}
     * Get a summary of all property analytics for an inputted country
     *
     * @param string $country
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getCountry(string $country)
    {
        $findCountry = Property::where('country', $country)->exists();

        if (!$findCountry) {
            return response()->json(['errorMsg' => 'Country not found'], 400);
        }

        $propertyIds = Property::where('country', $country)->pluck('id');

        return $this->propertyAnalyticsHandler->buildSummary('Country', $country, $propertyIds);
    }
}
