<?php

namespace Database\Seeders;

use App\Models\Work;
use App\Models\Series;
use App\Models\User;
use App\Models\Platform;
use App\Models\Marketplace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        $user = User::firstOrCreate(
            ['email' => 'admin@kdpmanager.local'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        // Crear usuarios adicionales
        User::firstOrCreate(
            ['email' => 'author1@kdpmanager.local'],
            [
                'name' => 'Author One',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'author2@kdpmanager.local'],
            [
                'name' => 'Author Two',
                'password' => Hash::make('password'),
            ]
        );

        // Crear series de ejemplo
        Series::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Aventuras en el Multiverso'],
            ['description' => 'Serie de fantasía épica con múltiples mundos']
        );

        Series::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Misterios de la Ciudad Perdida'],
            ['description' => 'Serie de misterio y aventura arqueológica']
        );

        // Crear obras de ejemplo
        Work::create([
            'user_id' => $user->id,
            'series_id' => Series::where('title', 'Aventuras en el Multiverso')->first()->id,
            'series_number' => 1,
            'title_internal' => 'El Portal de Cristal',
            'title_public' => 'El Portal de Cristal',
            'subtitle' => 'Un viaje hacia lo desconocido',
            'author_name' => 'Juan García',
            'pen_name' => 'J.G. Crystal',
            'genre' => 'fantasia',
            'subgenre' => 'fantasia_epica',
            'work_type' => 'original',
            'original_language' => 'es',
            'status' => 'publicada',
            'target_audience' => 'Adultos',
            'age_recommendation' => '16+',
            'description_marketing' => 'Una aventura épica a través de múltiples mundos y realidades alternativas.',
            'description_internal' => 'Obra maestra de la fantasía contemporánea con elementos de ciencia ficción.',
            'start_date' => now()->subYear(),
            'planned_publish_date' => now()->subMonths(6),
        ]);

        Work::create([
            'user_id' => $user->id,
            'series_id' => Series::where('title', 'Misterios de la Ciudad Perdida')->first()->id,
            'series_number' => 1,
            'title_internal' => 'Los Secretos de Lemuria',
            'title_public' => 'Los Secretos de Lemuria',
            'author_name' => 'María López',
            'pen_name' => 'M.L. Mystery',
            'genre' => 'misterio',
            'work_type' => 'original',
            'original_language' => 'es',
            'status' => 'redaccion',
            'target_audience' => 'Adultos',
            'age_recommendation' => '18+',
            'description_marketing' => 'Misterio arqueológico envuelto en una conspiración global.',
            'start_date' => now()->subMonths(3),
        ]);

        // Crear plataformas y marketplaces
        $kdp = Platform::firstOrCreate(
            ['name' => 'Amazon KDP'],
            ['description' => 'Kindle Direct Publishing']
        );

        $kdp_us = Marketplace::firstOrCreate(
            ['platform_id' => $kdp->id, 'code' => 'amazon.com'],
            ['name' => 'Amazon US', 'currency' => 'USD']
        );

        $kdp_es = Marketplace::firstOrCreate(
            ['platform_id' => $kdp->id, 'code' => 'amazon.es'],
            ['name' => 'Amazon España', 'currency' => 'EUR']
        );

        // Agregar más plataformas
        Platform::firstOrCreate(
            ['name' => 'SmashWords'],
            ['description' => 'Distribución digital multiformato']
        );

        Platform::firstOrCreate(
            ['name' => 'Draft2Digital'],
            ['description' => 'Distribución y publicación digital']
        );

        // Crear roles y permisos básicos
        $this->call(RolePermissionSeeder::class);
    }
}
