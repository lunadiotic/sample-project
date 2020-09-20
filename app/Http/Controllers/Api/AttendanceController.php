<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function today()
    {
        $attendaces = Attendance::whereDate('created_at', Carbon::today())
            ->with(['user'])->get();

        return response()->json([
            'message' => 'list of attendaces today',
            'data' => $attendaces,
        ], Response::HTTP_OK);
    }

    public function history(Request $request)
    {
        // $request->validate([
        //     'type' => ['required']
        // ]);

        if ($request->from && $request->to) {
            $data = $request->user()
                ->attendances()
                // ->where('status', $request->type)
                ->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to])
                ->paginate(10);
        } else {
            $data = $request->user()
                ->attendances()
                // ->where('status', $request->type)
                ->paginate(10);
        }

        return response()->json([
            'message' => "list of attendaces by user with {$request->type} status",
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function in(Request $request)
    {
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            // 'photo' => ['required']
        ]);

        $data = $request->user()->attendances()
            ->where('status', 'in')
            ->whereDate('created_at', Carbon::today())
            ->firstOr(function() use($request) {
                $path = $this->uploadPhoto($request->file('photo'), $request->user()->name);
                $attendace = $request->user()->attendances()->create([
                    'status' => 'in',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $path['name'],
                    'address' => $request->address
                ]);

                return $attendace;
            });

        return response()->json([
            'message' => 'user has been checked in',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function out(Request $request)
    {
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            // 'photo' => ['required']
        ]);

        $data = $request->user()->attendances()
            ->where('status', 'out')
            ->whereDate('created_at', Carbon::today())
            ->firstOr(function() use($request) {
                $path = $this->uploadPhoto($request->file('photo'), $request->user()->name);
                $attendace = $request->user()->attendances()->create([
                    'status' => 'out',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $path['name'],
                    'address' => $request->address
                ]);

                return $attendace;
            });

        return response()->json([
            'message' => 'user has been checked out',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function uploadPhoto($photo, $user)
    {
        $name = $user . '-' . time();
        $extension = $photo->getClientOriginalExtension();
        $newName = trim($name) . '.' . $extension;
        $path = Storage::putFileAs('public/photo', $photo, $newName);
        return [
            'name' => $newName,
            'path' => $path
        ];
    }
}
