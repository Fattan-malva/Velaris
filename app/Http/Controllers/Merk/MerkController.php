<?php

namespace App\Http\Controllers\Merk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merk;

class MerkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $merkes = Merk::all();
        return view('merk.index', compact('merkes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Merk::create([
            'name' => $request->name,
        ]);

        return redirect()->route('merk.index')->with('success', 'Merk created successfully.');
    }

    public function storeApi(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Buat data merk
            $merk = Merk::create([
                'name' => $request->name,
            ]);

            // Kembalikan respons JSON
            return response()->json([
                'success' => true,
                'message' => 'Merk created successfully.',
                'data' => $merk,
            ], 201);
        } catch (\Exception $e) {
            // Tangani error
            return response()->json([
                'success' => false,
                'message' => 'Failed to create merk.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $merk = Merk::findOrFail($id);
        return response()->json($merk);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $merk = Merk::findOrFail($id);
        $merk->update([
            'name' => $request->name,
        ]);

        return redirect()->route('merk.index')->with('success', 'Merk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $merk = Merk::findOrFail($id);
            $merk->delete();

            return redirect()->route('merk.index')->with('success', 'Merk deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch the foreign key constraint exception
            return redirect()->route('merk.index')->with('error', 'Unable to delete Merk. It is still referenced in other records.');
        }
    }

}
