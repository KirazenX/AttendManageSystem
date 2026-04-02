<?php

namespace App\Services;

use App\Models\GpsValidation;
use App\Models\OfficeLocation;

class GpsValidationService
{
    public function validate(
        float $latitude,
        float $longitude,
        string $type,
        int $attendanceId = null,
        int $userId = null
    ): array {
        $offices       = OfficeLocation::where('is_active', true)->get();
        $closestOffice = null;
        $minDistance   = PHP_INT_MAX;
        $isValid       = false;
        $validOffice   = null;

        foreach ($offices as $office) {
            $distance = $this->haversine($latitude, $longitude, $office->latitude, $office->longitude);

            // Selalu cari kantor terdekat
            if ($distance < $minDistance) {
                $minDistance   = $distance;
                $closestOffice = $office;
            }

            // Cek validitas tapi JANGAN break — lanjutkan cari yang lebih dekat
            if ($distance <= $office->radius_meters) {
                $isValid = true;
                // Simpan kantor valid yang paling dekat
                if ($validOffice === null || $distance < $this->haversine(
                    $latitude, $longitude, $validOffice->latitude, $validOffice->longitude
                )) {
                    $validOffice = $office;
                }
            }
        }

        // Prioritaskan kantor valid jika ada, fallback ke terdekat
        $targetOffice = $validOffice ?? $closestOffice;

        if ($userId) {
            GpsValidation::create([
                'user_id'            => $userId,
                'attendance_id'      => $attendanceId,
                'office_location_id' => $targetOffice?->id,
                'type'               => $type,
                'user_latitude'      => $latitude,
                'user_longitude'     => $longitude,
                'distance_meters'    => (int) $minDistance,
                'is_valid'           => $isValid,
            ]);
        }

        return [
            'is_valid'        => $isValid,
            'distance_meters' => (int) $minDistance,
            'office'          => $targetOffice,
        ];
    }

    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}