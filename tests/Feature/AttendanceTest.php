<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    public function testAttendanceIn()
    {
        $token = '3VSiC6IxpmmsZP21SSRuWdz1CEsjs1s1IWWLqDCHak0Uux8GIggjoHQ2Fd5ACFfm1rU6Y0EkG5dwxnJA';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->json('POST', '/api/attendance/in', [
            "long" => "3.123",
            "lat" => "-12.134",
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                "message",
                "data" => [
                    "status",
                    "long",
                    "lat",
                    "user_id",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }

    public function testAttendanceOut()
    {
        $token = '3VSiC6IxpmmsZP21SSRuWdz1CEsjs1s1IWWLqDCHak0Uux8GIggjoHQ2Fd5ACFfm1rU6Y0EkG5dwxnJA';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->json('POST', '/api/attendance/out', [
            "long" => "3.123",
            "lat" => "-12.134",
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                "message",
                "data" => [
                    "status",
                    "long",
                    "lat",
                    "user_id",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }
}
