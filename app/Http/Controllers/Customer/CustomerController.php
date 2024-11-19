<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('customer.create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:customer',
            'password' => 'required|string|max:50',
            'role' => 'required|string|max:50',
            'nrp' => 'required|string|max:50|unique:customer',
            'name' => 'required|string|max:50|regex:/^[\p{L}\s]+$/u',
            'mapping' => 'nullable|string|max:50',
        ]);

        $customer = Customer::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')), // Hash the password
            'role' => $request->input('role'),
            'nrp' => $request->input('nrp'),
            'name' => $request->input('name'),
            'mapping' => $request->input('mapping'),
        ]);


        return redirect()->route('customer.index')->with('success', 'User created successfully.');
    }

    // Display the specified resource
    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    // Show the form for editing the specified resource
    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    // Update the specified resource in storage
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:customer,username,' . $customer->id,
            'password' => 'nullable|string|max:200', // Allow null for password
            'role' => 'required|string|max:50',
            'name' => 'required|string|max:50|regex:/^[\p{L}\s]+$/u',
            'mapping' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['username', 'role', 'nrp', 'name', 'mapping']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password')); // Hash password if provided
        }

        $customer->update($data);



        return redirect()->route('customer.index')->with('success', 'User updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(Customer $customer)
    {
        // Check if the customer is referenced in the 'transactions' table
        $hasTransactions = \DB::table('transactions')->where('name_holder', $customer->id)->exists();

        if ($hasTransactions) {
            // If there are transactions related to the customer, don't delete
            return redirect()->route('customer.index')->with('error', 'This customer has associated transactions and cannot be deleted.');
        }
        // If no related transactions, proceed with deletion
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
    }



    public function editUser($id)
    {
        $user = Customer::findOrFail($id);
        return view('customer.editUser', compact('user'));
    }

    // Update the user profile

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'nrp' => 'required|string|max:50|unique:customer,nrp,' . $id,
            'name' => 'required|string|max:50|regex:/^[\p{L}\s]+$/u',
            'mapping' => 'required|string|max:50',
            'email' => 'required|string|max:255|unique:customer,username,' . $id, // Ensure unique email
        ]);

        $user = Customer::findOrFail($id);
        $user->update([
            'nrp' => $request->nrp,
            'name' => $request->name,
            'mapping' => $request->mapping,
            'username' => $request->email, // Assuming this is the email field
        ]);


        return response()->json(['success' => true]);
    }

}
