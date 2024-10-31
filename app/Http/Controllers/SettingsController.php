<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function adminSettings()
    {
        $users = User::all(); // Get all users for the dropdown
        return view('pengaturan.pengaturanAdmin', compact('users'));
    }

    public function userSettings()
    {
        return view('pengaturan.pengaturanUser');
    }

    public function resetAdminPassword(Request $request)
    {

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'new_password_confirmation' => 'required|string|min:8',
        ]);



        $admin = Auth::user();

        if ($request->new_password != $request->new_password_confirmation) {
            return back()->with('error', 'Konfirmasi password tidak sama, coba kembali.');
        }

        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->with('error', 'Password gagal diganti!');
        }

        $admin->password = Hash::make($request->new_password);
        // dd($admin);
        $admin->save();

        Auth::logout();
        return redirect()->route('settings.admin')->with('success', 'Password berhasil diganti!');
    }

    public function resetUserPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make('12345678'); // Reset to default password
        $user->save();

        return redirect()->route('settings.admin')->with('success', 'User password has been reset to 12345678');
    }
}
