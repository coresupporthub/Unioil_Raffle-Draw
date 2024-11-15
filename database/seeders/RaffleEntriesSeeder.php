<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customers;
use App\Models\QrCode;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RaffleEntriesSeeder extends Seeder
{
    public function run()
    {
        $customers = Customers::all();
        $qrCodes = QrCode::all();
        $events = Event::all();

        if ($customers->isEmpty() || $qrCodes->isEmpty() || $events->isEmpty()) {
            $this->command->error('Customers, QR Codes, or Events table is empty. Please seed them first.');
            return;
        }


        foreach (range(1, 50) as $index) {
            // Random customer, QR code, and event
            $customer = $customers->random();
            $qrCode = $qrCodes->random();
            $event = $events->random();

            // Insert raffle entry
            DB::table('raffle_entries')->insert([
                'entries_id' => Str::uuid(),
                'customer_id' => $customer->customer_id,
                'event_id' => $event->event_id,
                'serial_number' => strtoupper(Str::random(10)),
                'qr_id' => $qrCode->qr_id,
                'retail_store_code' => strtoupper(Str::random(6)),
                'claim_status' => 'none',
                'winner_status' => 'false',
            ]);
        }
    }
}

