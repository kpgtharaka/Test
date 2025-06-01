<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\Customer;
use App\Models\Phone;
use App\Models\RepairPart;
use App\Models\RepairJobPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairs = Repair::with(['customer', 'phone'])->latest()->paginate(10);
        return view('admin.repairs.index', compact('repairs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $phones = Phone::select('id', 'brand', 'model', 'imei')->orderBy('brand')->orderBy('model')->get();
        return view('admin.repairs.create', compact('customers', 'phones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'phone_id' => 'required|exists:phones,id',
            'description_of_problem' => 'required|string',
            'status' => 'required|string|max:50',
            'estimated_cost' => 'nullable|numeric|min:0',
            'date_received' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $repair = Repair::create($validatedData);

        return redirect()->route('admin.repairs.edit', $repair)->with('success', 'Repair order created successfully. You can now add parts.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Repair $repair)
    {
        $repair->load(['customer', 'phone', 'partsUsed', 'jobParts.repairPart']);
        $repairParts = RepairPart::orderBy('name')->get();
        return view('admin.repairs.edit', compact('repair', 'repairParts')); // Re-using edit view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repair $repair)
    {
        $repair->load(['customer', 'phone', 'partsUsed', 'jobParts.repairPart']);
        $repairParts = RepairPart::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get(); // In case needed for reassignment, though less common for repairs
        $phones = Phone::orderBy('brand')->orderBy('model')->get(); // In case needed

        return view('admin.repairs.edit', compact('repair', 'repairParts', 'customers', 'phones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repair $repair)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id', // Usually not changed, but good to have
            'phone_id' => 'required|exists:phones,id',       // Usually not changed
            'description_of_problem' => 'required|string',
            'status' => 'required|string|max:50',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'date_received' => 'required|date',
            'date_completed' => 'nullable|date|after_or_equal:date_received',
            'notes' => 'nullable|string',
        ]);

        $repair->update($validatedData);

        return redirect()->route('admin.repairs.edit', $repair)->with('success', 'Repair order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repair $repair)
    {
        DB::beginTransaction();
        try {
            // Revert stock for parts used in this repair
            foreach ($repair->jobParts as $jobPart) {
                $part = $jobPart->repairPart;
                if ($part) {
                    $part->increment('stock_quantity', $jobPart->quantity_used);
                }
            }
            // Job parts will be deleted by cascade if DB constraints are set, or manually:
            // $repair->jobParts()->delete();
            $repair->delete();
            DB::commit();
            return redirect()->route('admin.repairs.index')->with('success', 'Repair order deleted and stock reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.repairs.index')->with('error', 'Error deleting repair: ' . $e->getMessage());
        }
    }

    /**
     * Add a part to the repair.
     */
    public function addPart(Request $request, Repair $repair)
    {
        $validatedData = $request->validate([
            'repair_part_id' => 'required|exists:repair_parts,id',
            'quantity_used' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $repairPart = RepairPart::findOrFail($validatedData['repair_part_id']);

            if ($repairPart->stock_quantity < $validatedData['quantity_used']) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Not enough stock for ' . $repairPart->name . '. Available: ' . $repairPart->stock_quantity);
            }

            RepairJobPart::create([
                'repair_id' => $repair->id,
                'repair_part_id' => $repairPart->id,
                'quantity_used' => $validatedData['quantity_used'],
                'price_at_time_of_repair' => $repairPart->selling_price, // Log current selling price
            ]);

            $repairPart->decrement('stock_quantity', $validatedData['quantity_used']);

            DB::commit();
            return redirect()->route('admin.repairs.edit', $repair)->with('success', 'Part added to repair successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding part: ' . $e->getMessage());
        }
    }

    /**
     * Remove a part from the repair.
     */
    public function removePart(Repair $repair, RepairJobPart $repairJobPart) // Route model binding
    {
        DB::beginTransaction();
        try {
            $part = $repairJobPart->repairPart; // Assumes RepairJobPart has repairPart relationship
            if ($part) {
                $part->increment('stock_quantity', $repairJobPart->quantity_used);
            }

            $repairJobPart->delete();

            DB::commit();
            return redirect()->route('admin.repairs.edit', $repair)->with('success', 'Part removed from repair and stock reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error removing part: ' . $e->getMessage());
        }
    }
}
