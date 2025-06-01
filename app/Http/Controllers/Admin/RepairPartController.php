<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairPart;
use Illuminate\Http\Request;

class RepairPartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairParts = RepairPart::latest()->paginate(10);
        return view('admin.repair_parts.index', compact('repairParts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.repair_parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:repair_parts,sku',
            'description' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        RepairPart::create($validatedData);

        return redirect()->route('admin.repair-parts.index')->with('success', 'Repair part created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairPart $repairPart)
    {
        return view('admin.repair_parts.edit', compact('repairPart')); // Re-using edit view for show
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairPart $repairPart)
    {
        return view('admin.repair_parts.edit', compact('repairPart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairPart $repairPart)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:repair_parts,sku,' . $repairPart->id,
            'description' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $repairPart->update($validatedData);

        return redirect()->route('admin.repair-parts.index')->with('success', 'Repair part updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairPart $repairPart)
    {
        // Consider implications if part is in use.
        // For now, direct delete. Cascade should handle repair_job_parts if set up.
        try {
            $repairPart->delete();
            return redirect()->route('admin.repair-parts.index')->with('success', 'Repair part deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Likely a foreign key constraint violation
            return redirect()->route('admin.repair-parts.index')->with('error', 'Could not delete repair part. It might be associated with existing repairs.');
        }
    }
}
