<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateExistingDataWithUserIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user to assign existing data to
        $adminUser = \App\Models\User::whereHas('role', function($query) {
            $query->where('role_name', 'anggota');
        })->first();

        if ($adminUser) {
            // Update existing rencana kegiatans with user_id
            \App\Models\RencanaKegiatan::whereNull('user_id')->update([
                'user_id' => $adminUser->id
            ]);

            // Update existing laporan kegiatans with user_id
            \App\Models\LaporanKegiatan::whereNull('user_id')->update([
                'user_id' => $adminUser->id
            ]);

            $this->command->info('Existing data updated with user_id: ' . $adminUser->id);
        } else {
            $this->command->error('No admin user found to assign existing data');
        }
    }
}
