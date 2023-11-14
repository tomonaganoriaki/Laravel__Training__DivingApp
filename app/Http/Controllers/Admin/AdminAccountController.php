<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    public function index(): View
    {
        $admins = Admin::all();
        return view('admin.account.index')->with('admins', $admins);
    }

    public function create(): View
    {
        return view('admin.account.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed'],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session()->flash('flash_message', '管理者アカウントを作成しました。');
        return redirect()->route('admin.account.index');
    }
    public function edit($id): View
    {
        $admin = Admin::findOrFail($id);
        return view('admin.account.edit')->with('admin', $admin);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $admin = Admin::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $admin->name = $request->name;
        $admin->email = strtolower($request->email);
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        session()->flash('flash_message', '管理者アカウントを更新しました。');
        return redirect()->route('admin.account.index');
    }

    public function destroy($id): RedirectResponse
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        session()->flash('flash_message', '管理者アカウントを削除しました。');
        return redirect()->route('admin.account.index');
    }

}
