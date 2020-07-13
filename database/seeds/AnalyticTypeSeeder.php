<?php

use App\Models\AnalyticType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class AnalyticTypeSeeder extends Seeder
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
        Log::info('Seeding analytic type data started');

        $file = storage_path('app/BackEndTest_TestData_v1.1.xlsx');

        $analyticTypes = (new FastExcel())->sheet(2)->import($file);

        foreach ($analyticTypes as $analyticType) {
            AnalyticType::create([
                'id' => $analyticType['id'],
                'name' => $analyticType['name'],
                'units' => $analyticType['units'],
                'is_numeric' => $analyticType['is_numeric'],
                'num_decimal_places' => $analyticType['num_decimal_places'],
            ]);
        }

        Log::info('Seeding analytic type data finished');
    }
}
