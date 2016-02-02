<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = \App\User::get();
        return view('backend.users.index', compact('users'));
    }

    public function show(Request $request, $id) {
        $user = \App\User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }

    public function edit(Request $request, $id) {
        $user = \App\User::findOrFail($id);
        return view('backend.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = \App\User::findOrFail($id);
        $this->validate($request, [
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed'
        ]);
        $user->name = strtolower($request->input('email'));
        if($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
        return redirect('backend/users/' . $user->id);
    }

    public function destroy(Request $request, $id)
    {
        $user = \App\User::findOrFail($id);
        if(!$user->isAdmin()) {
            $user->delete();
        }

        return redirect()->to('backend/users');
    }
}
