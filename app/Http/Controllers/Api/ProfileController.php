<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,' . $user->id]
        ]);

        $photo = $request->file('photo');

        if ($photo) {
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $name = $request->name . '-' . time();
            $extension = $photo->getClientOriginalExtension();
            $newName = $name . '.' . $extension;
            Storage::putFileAs('public/profile', $photo, $newName);
        } else {
            $newName = $user->photo;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'photo' => $newName
        ]);

        return response()->json([
            'message' => 'user has been updated',
            'data' => $request->user(),
        ], Response::HTTP_OK);
    }
}
