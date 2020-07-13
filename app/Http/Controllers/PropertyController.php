<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * PropertyController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * POST - /property
     * Add a new property
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate($this->request, [
            'suburb' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        Property::create(array_merge($this->request->all(), [
            'guid' => (string) Str::uuid(),
        ]));

        return response()->json(['data' => 'Property created successfully'], 200);
    }
}
