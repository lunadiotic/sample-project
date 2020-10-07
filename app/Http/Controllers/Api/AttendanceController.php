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
        $request->validate([
            'from' => ['required'],
            'to' => ['required'],
            'type' => ['in:in,out']
        ]);

        $data = $request->user()
                ->attendances()
                ->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to])
                ->where('status', $request->type)
                ->orderBy('created_at')
                ->get();

        return response()->json([
            'message' => "list of attendaces by user with {$request->type} status",
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function getHistory(Request $request)
    {
        $request->validate([
            'from' => ['required'],
            'to' => ['required'],
        ]);

        $data = DB::select("SELECT name, ain.created_at as tanggal_in, aout.created_at as tanggal_out
            from users u
            JOIN attendances ain ON ain.user_id = u.id
            JOIN attendances aout ON aout.user_id = u.id
            where u.id = {$request->user()->id}
            and ain.status = 'in'
            and aout.status = 'out'
            and DATE(ain.created_at) = DATE(aout.created_at)"
        );

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
            'photo' => ['required']
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
            'photo' => ['required']
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
        $name = \Str::slug($user) . '-' . time();
        $extension = $photo->getClientOriginalExtension();
        $newName = $name . '.' . $extension;
        $path = Storage::putFileAs('public/photo', $photo, $newName);
        return [
            'name' => $newName,
            'path' => $path
        ];
    }
}
