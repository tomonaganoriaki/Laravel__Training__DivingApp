<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.account.index')->with('admins', $admins);
    }

    public function create()
    {
        return view('admin.account.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed'],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        session()->flash('flash_message', '管理者アカウントを作成しました。');
        return redirect()->route('admin.account.index');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        session()->flash('flash_message', '管理者アカウントを削除しました。');
        return redirect()->route('admin.account.index');
    }

}
