<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('warehouse');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'cashiers' => User::where('role', 'cashier')->count(),
            'storekeepers' => User::where('role', 'storekeeper')->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $roles = [
            'admin' => 'মালিক / সুপার অ্যাডমিন (Admin) - পূর্ণ নিয়ন্ত্রণ',
            'manager' => 'শপ ম্যানেজার (Manager) - সেলস, পারচেজ, স্টক ও কর্মী নিয়ন্ত্রণ',
            'cashier' => 'কাউন্টার ক্যাশিয়ার (Cashier) - পিওএস বিলিং, কোটেশন ও মানি রিসিট',
            'storekeeper' => 'গোডাউন ইন-চার্জ (Storekeeper) - স্টক এন্ট্রি, ওয়্যারহাউস স্থানান্তর ও স্ক্র্যাপ',
        ];

        return view('users.create', compact('warehouses', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'role' => 'required|in:admin,manager,cashier,storekeeper',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'password' => ['required', 'confirmed', Password::min(6)],
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'নতুন ব্যবহারকারী সফলভাবে তৈরি করা হয়েছে!');
    }

    public function edit(User $user)
    {
        $warehouses = Warehouse::all();
        $roles = [
            'admin' => 'মালিক / সুপার অ্যাডমিন (Admin) - পূর্ণ নিয়ন্ত্রণ',
            'manager' => 'শপ ম্যানেজার (Manager) - সেলস, পারচেজ, স্টক ও কর্মী নিয়ন্ত্রণ',
            'cashier' => 'কাউন্টার ক্যাশিয়ার (Cashier) - পিওএস বিলিং, কোটেশন ও মানি রিসিট',
            'storekeeper' => 'গোডাউন ইন-চার্জ (Storekeeper) - স্টক এন্ট্রি, ওয়্যারহাউস স্থানান্তর ও স্ক্র্যাপ',
        ];

        return view('users.edit', compact('user', 'warehouses', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'role' => 'required|in:admin,manager,cashier,storekeeper',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'is_active' => 'boolean',
        ]);

        // Prevent admin from deactivating or demoting themselves
        if ($user->id === auth()->id() && (!$request->boolean('is_active') || $validated['role'] !== 'admin')) {
            return back()->with('error', 'আপনি নিজের অ্যাডমিন পদমর্যাদা বা সক্রিয় স্থিতি পরিবর্তন করতে পারবেন না!');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];
        $user->warehouse_id = $validated['warehouse_id'] ?? null;
        $user->is_active = $request->boolean('is_active');

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', "ব্যবহারকারী '{$user->name}'-এর তথ্য সফলভাবে আপডেট হয়েছে!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজেকে মুছে ফেলতে পারবেন না!');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "ব্যবহারকারী '{$name}' মুছে ফেলা হয়েছে!");
    }
}
