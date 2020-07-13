<?php

use App\Models\PropertyAnalytics;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class PropertyAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function run()
    {
        Log::info('Seeding property analytics data started');

        $file = storage_path('uploaded-data/BackEndTest_TestData_v1.1.xlsx');

        $propertyAnalytics = (new FastExcel())->sheet(3)->import($file);

        foreach ($propertyAnalytics as $propertyAnalytic) {
            PropertyAnalytics::create([
                'property_id' => $propertyAnalytic['property_id'],
                'analytic_type_id' => $propertyAnalytic['analytic_type_id'],
                'value' => $propertyAnalytic['value'],
            ]);
        }

        Log::info('Seeding property analytics data finished');
    }
}
