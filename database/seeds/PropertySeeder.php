<?php

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class PropertySeeder extends Seeder
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
        Log::info('Seeding property data started');

        $file = storage_path('uploaded-data/BackEndTest_TestData_v1.1.xlsx');

        $properties = (new FastExcel())->sheet(1)->import($file);

        foreach ($properties as $property) {
            Property::create([
                'id' => $property['Property Id'],
                'guid' => (string) Str::uuid(),
                'suburb' => $property['Suburb'],
                'state' => $property['State'],
                'country' => $property['Country'],
            ]);
        }

        Log::info('Seeding property data finished');
    }
}
