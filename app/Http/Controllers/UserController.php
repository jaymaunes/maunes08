<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Apply admin middleware to all methods except profile and updateProfile
        $this->middleware('admin')->except(['profile', 'updateProfile']);
    }

    public function index()
    {
        try {
            $users = User::orderBy('created_at', 'desc')->paginate(10);
            return view('users.index', compact('users'));
        } catch (Throwable $e) {
            return redirect()->back()
                ->with('error', 'Error loading users: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed'
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_admin' => false // Set default value for is_admin
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('users.edit', compact('user'));
        } catch (Throwable $e) {
            return redirect()->route('users.index')->with('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)]
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['required', 'string', 'min:8', 'confirmed']
                ]);
                $user->password = Hash::make($request->password);
            }

            $user->save();
            DB::commit();

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if (auth()->id() === $user->id) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            if ($user->is_admin) {
                return back()->with('error', 'Admin users cannot be deleted.');
            }

            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function makeAdmin($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->is_admin) {
                return back()->with('error', 'User is already an admin.');
            }

            DB::beginTransaction();
            $user->is_admin = true;
            $user->save();
            DB::commit();

            return back()->with('success', 'User has been made an admin successfully.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error making user admin: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['required', 'string', 'min:8', 'confirmed']
                ]);
                $user->password = Hash::make($request->password);
            }

            $user->save();
            DB::commit();

            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
}
