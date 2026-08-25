<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'role' => $user->role,

                'nis_nip' => $user->nis_nip,

                'phone' => $user->phone,

                'address' => $user->address,

                'points' => $user->points ?? 0,

                'badge' => $user->badge ?? 'Bronze',
            ]
        ]);
    }
    public function ranking($id)
{
    $user = User::findOrFail($id);

    $rank = User::where(
        'role',
        $user->role
    )
    ->where(
        'points',
        '>',
        $user->points
    )
    ->count() + 1;

    $total = User::where(
        'role',
        $user->role
    )->count();

    return response()->json([

        'success' => true,

        'rank' => $rank,

        'total' => $total,
    ]);
}
public function leaderboard($role)
{
    $users = User::where(
        'role',
        $role
    )
    ->orderByDesc('points')
    ->take(20)
    ->get([
        'id',
        'name',
        'points',
        'badge'
    ]);

    return response()->json([

        'success' => true,

        'data' => $users
    ]);
}


public function changePassword(Request $request)
{
    $request->validate([

        'user_id' => 'required|exists:users,id',

        'old_password' => 'required',

        'new_password' => 'required|min:6',
    ]);

    $user = User::findOrFail(
        $request->user_id
    );

    if (!Hash::check(
        $request->old_password,
        $user->password
    )) {

        return response()->json([

            'success' => false,

            'message' =>
                'Password lama salah'
        ]);
    }

    $user->update([

        'password' =>
            Hash::make(
                $request->new_password
            )
    ]);

    return response()->json([

        'success' => true,

        'message' =>
            'Password berhasil diubah'
    ]);
}
public function updateProfile(Request $request)
{
    $request->validate([

        'user_id' => 'required|exists:users,id',

        'name' => 'required|max:255',

        'email' => 'required|email|unique:users,email,' . $request->user_id,

        'phone' => 'nullable|max:20',

        'address' => 'nullable|max:255',

    ]);

    $user = User::findOrFail($request->user_id);

    $user->update([

        'name' => $request->name,

        'email' => $request->email,

        'phone' => $request->phone,

        'address' => $request->address,

    ]);

    return response()->json([

        'success' => true,

        'message' => 'Profil berhasil diperbarui',

        'data' => $user,

    ]);
}
}