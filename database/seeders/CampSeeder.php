<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Camps;

class CampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $camps = [
            [
                'title'         => 'Pro Ngoding',
                'slug'          => 'pro-ngoding',
                'price'         => 350,
                'created_at'    => date('Y-m-d H:i:s', time()),
                'updated_at'    => date('Y-m-d H:i:s', time()),
            ],
            [
                'title'         => 'Mulai Ngoding',
                'slug'          => 'mulai-ngoding',
                'price'         => 75,
                'created_at'    => date('Y-m-d H:i:s', time()),
                'updated_at'    => date('Y-m-d H:i:s', time()),
            ],
        ];

        // 1st method
        // foreach ($camps as $key => $camp) {
        //     Camps::create($camp);
        // }

        // 2nd method
        Camps::insert($camps);
    }
}
