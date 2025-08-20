<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema as FacadesSchema;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Event as FacadesEvent;
use Illuminate\Support\Facades\App as FacadesApp;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Lang as FacadesLang;
use Illuminate\Support\Facades\URL as FacadesURL;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Queue as FacadesQueue;
use Illuminate\Support\Facades\Redirect as FacadesRedirect;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Bus as FacadesBus;
use Illuminate\Support\Facades\Cookie as FacadesCookie;
use Illuminate\Support\Facades\Pipeline as FacadesPipeline;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $data = [
            [
                'name' => 'Editorial Alfa',
                'contact_person' => 'María López',
                'phone' => '555-1001',
                'email' => 'contacto@editorialalfa.com',
                'address' => 'Av. Principal 123, Ciudad',
                'tax_id' => 'ALF-123456',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Distribuidora Beta',
                'contact_person' => 'Carlos Pérez',
                'phone' => '555-1002',
                'email' => 'ventas@distribuidorabeta.com',
                'address' => 'Calle Secundaria 456, Ciudad',
                'tax_id' => 'BET-654321',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Papelería Gamma',
                'contact_person' => 'Lucía Gómez',
                'phone' => '555-1003',
                'email' => 'info@papeleriagamma.com',
                'address' => 'Boulevard Central 789, Ciudad',
                'tax_id' => 'GAM-789123',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'OfiMax',
                'contact_person' => 'Jorge Díaz',
                'phone' => '555-1004',
                'email' => 'contacto@ofimax.com',
                'address' => 'Parque Industrial 321, Ciudad',
                'tax_id' => 'OFI-321987',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($data as $row) {
            DB::table('suppliers')->updateOrInsert(
                ['name' => $row['name']],
                [
                    'contact_person' => $row['contact_person'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'address' => $row['address'],
                    'tax_id' => $row['tax_id'],
                    'status' => $row['status'],
                    'updated_at' => $now,
                    'created_at' => $row['created_at'] ?? $now,
                ]
            );
        }
    }
}
