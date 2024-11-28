<?php

namespace App\Http\Services;

use App\Models\RaffleEntries;
use App\Models\Event;
use App\Http\Services\Magic;
use App\Models\ActivityLogs;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class Tools
{

    public static function genCode(int $length = 15, string $type = "alphanumeric"): string
    {
        switch ($type) {
            case 'numeric':
                $characters = '0123456789';
                break;
            default:
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function CreateEntries(string $customer_id, string $qr_id, string $store_code): string
    {
        $serialNumber = Tools::genCode();

        $checkSerialNumber = RaffleEntries::where('serial_number', $serialNumber)->first();

        while ($checkSerialNumber) {
            $serialNumber = Tools::genCode();
            $checkSerialNumber = RaffleEntries::where('serial_number', $serialNumber)->first();
        }

        $entry = new RaffleEntries();

        $currentActiveEvent = Event::where('event_status', Magic::ACTIVE_EVENT)->first();

        $entry->event_id = $currentActiveEvent->event_id;

        $entry->customer_id = $customer_id;
        $entry->serial_number = $serialNumber;
        $entry->qr_id = $qr_id;
        $entry->retail_store_code = $store_code;
        $entry->save();

        return $serialNumber;
    }
    /**
     * Reads a CSV file and returns its content as an array of rows.
     *
     * @param UploadedFile $file The uploaded CSV file.
     * @return array<int, array<int, string|null>> Each row as an array of column values.
     */
    public static function readCSV(UploadedFile $file): array
    {
        $rows = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }

        /**
        * @param array<string> $actions
        * @param array<string, string|bool> $response
        */
    public static function Logger($request, array $actions, array $response): void
    {

        $logs = new ActivityLogs();

        $logs->user_id = (int)Auth::id();
        $logs->action = $actions[0];
        $logs->result = $actions[1];
        $logs->device = $request->userAgent();
        $logs->page_route =  $request->headers->get('referer');
        $logs->api_calls = $request->path();
        $logs->request_type = $request->method();
        $logs->session_id = $request->session()->getId();
        $logs->sent_data = $request->all();
        $logs->response_data = $response;
        $logs->save();
    }

    /**
    * @param array<mixed> $data
    * @param mixed $searchValue
    * @return array<mixed>
    */
    public static function searchInArray(array $data, $searchValue): array
    {
        return array_filter($data, function ($item) use ($searchValue) {
            return in_array($searchValue, $item);
        });
    }
}
