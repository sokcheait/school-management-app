<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            ['model' => \App\Models\Province::class, 'key' => 'province_code', 'file' => 'address/ProvinceList2025.csv'],
            ['model' => \App\Models\District::class, 'key' => 'district_code', 'file' => 'address/DistrictList2025.csv'],
            ['model' => \App\Models\Commune::class,  'key' => 'commune_code',  'file' => 'address/CommuneList2025.csv'],
            ['model' => \App\Models\Village::class,  'key' => 'village_code',  'file' => 'address/VillagesList2025.csv'],
        ];

        DB::beginTransaction();
        try {
            foreach ($entities as $entity) {
                $path = database_path("seeders/data/{$entity['file']}");

                if (!file_exists($path)) {
                    $this->command->error("File not found: {$entity['file']}");
                    continue;
                }

                $this->seedCsv($path, $entity['model'], $entity['key']);
            }
            DB::commit();
            $this->command->info('Full address hierarchy seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Seeding failed. Transaction rolled back: ' . $e->getMessage());
        }
    }

    private function seedCsv($path, $model, $uniqueKey)
    {
        $handle = fopen($path, 'r');
        
        // Clean BOM and white spaces from headers
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => trim(str_replace("\xEF\xBB\xBF", '', $h)), $header);

        $this->command->getOutput()->progressStart();

        while (($row = fgetcsv($handle)) !== false) {
            $item = array_combine($header, $row);
            
            $model::updateOrCreate(
                [$uniqueKey => $item[$uniqueKey]], 
                $item
            );
            
            $this->command->getOutput()->progressAdvance();
        }

        fclose($handle);
        $this->command->getOutput()->progressFinish();
        $this->command->info("Finished: " . basename($path));
    }
}
