<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage    = $request->input('per_page', 10);

        $users      = User::query()
            ->select('id', 'name', 'email', 'role', 'provider', 'created_at')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('role', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Dashboard/User/Index', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => 'required|in:admin,user'
        ]);

        $params = [
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'provider'  => 'web'
        ];

        User::create($params);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($id),
            ],
            'role'      => 'required|in:admin,user'
        ]);

        $user = User::find($id);

        $params = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role']
        ];

        $user->update($params);

        return back()->with('success', 'User berhasil diubah!');
    }

    /**
     * Reset password for the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->provider == 'google') {
            return back()->with('error', 'Tidak dapat mereset password untuk user Google.');
        }

        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil direset.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}
