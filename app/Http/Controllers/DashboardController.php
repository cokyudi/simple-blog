<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function getLoggedInUser() {
        $user = Auth::user();
        return response()->json(['error' => false, 'data' => $user]);
    }

    public function getAllUser() {
        $users = User::where('type', 0)->get();
        return response()->json(['error' => false, 'data' => $users]);
    }

    public function getUserById($id) {
        $user = User::where('id', $id)->first();
        return response()->json(['error' => false, 'data' => $user]);
    }

    public function createUser(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['error' => false, 'data' => $user]);
    }

    public function editUser(Request $request) {
        $user = User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['error' => false, 'data' => $user]);
    }

    public function deleteUser(Request $request) {
        $user = User::where('id', $request->id)->delete();
        return response()->json(['error' => false, 'data' => $user]);
    }
}
