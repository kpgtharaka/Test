<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use App\Models\Customer;
use App\Models\PhoneOwnerHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Added for potential transaction
use Carbon\Carbon; // Added for date manipulation

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load current owner information.
        // Assuming 'currentOwner' relationship on Phone model returns the current owner record from phone_owner_histories.
        // And that PhoneOwnerHistory has a 'customer' relationship.
        $phones = Phone::with(['currentOwner.customer'])->latest()->paginate(10);
        return view('admin.phones.index', compact('phones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('admin.phones.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'imei' => 'required|string|max:255|unique:phones,imei',
            'serial_number' => 'required|string|max:255|unique:phones,serial_number',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'purchase_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $phone = Phone::create([
                'imei' => $validatedData['imei'],
                'serial_number' => $validatedData['serial_number'],
                'brand' => $validatedData['brand'],
                'model' => $validatedData['model'],
            ]);

            if (!empty($validatedData['customer_id']) && !empty($validatedData['purchase_date'])) {
                PhoneOwnerHistory::create([
                    'phone_id' => $phone->id,
                    'customer_id' => $validatedData['customer_id'],
                    'purchase_date' => $validatedData['purchase_date'],
                    'sale_date' => null, // New phone, so no sale date yet
                ]);
            }
            DB::commit();
            return redirect()->route('admin.phones.index')->with('success', 'Phone created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error creating phone: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Phone $phone)
    {
        $phone->load(['ownershipHistory.customer', 'repairHistory.customer']);
        $customers = Customer::orderBy('name')->get(); // For assigning new owner
        return view('admin.phones.edit', compact('phone', 'customers')); // Re-using edit view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phone $phone)
    {
        $phone->load(['ownershipHistory.customer', 'repairHistory.customer', 'currentOwner.customer']);
        $customers = Customer::orderBy('name')->get();
        return view('admin.phones.edit', compact('phone', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone)
    {
        $validatedPhoneData = $request->validate([
            'imei' => 'required|string|max:255|unique:phones,imei,' . $phone->id,
            'serial_number' => 'required|string|max:255|unique:phones,serial_number,' . $phone->id,
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
        ]);

        $validatedOwnershipData = $request->validate([
            'new_customer_id' => 'nullable|exists:customers,id',
            'new_purchase_date' => 'nullable|date',
            'clear_current_owner' => 'nullable|boolean', // Hidden field if we want to make phone unassigned
        ]);

        DB::beginTransaction();
        try {
            $phone->update($validatedPhoneData);

            $currentOwnerHistory = $phone->ownershipHistory()->whereNull('sale_date')->first();

            if ($request->has('clear_current_owner') && $request->clear_current_owner && $currentOwnerHistory) {
                 // Mark current owner as sold
                $currentOwnerHistory->update(['sale_date' => Carbon::now()->toDateString()]);
            } elseif (!empty($validatedOwnershipData['new_customer_id']) && !empty($validatedOwnershipData['new_purchase_date'])) {
                $newPurchaseDate = Carbon::parse($validatedOwnershipData['new_purchase_date']);

                // Ensure new purchase date is not before the phone's creation or last sale date
                if ($currentOwnerHistory && $currentOwnerHistory->purchase_date && $newPurchaseDate->lessThan(Carbon::parse($currentOwnerHistory->purchase_date))) {
                     DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'New purchase date cannot be before the previous owner\'s purchase date.');
                }


                if ($currentOwnerHistory) {
                    // If new owner is different or purchase date is different
                    if ($currentOwnerHistory->customer_id != $validatedOwnershipData['new_customer_id'] ||
                        Carbon::parse($currentOwnerHistory->purchase_date)->notEqualTo($newPurchaseDate)) {

                        // Set sale date for current owner (can be new purchase date or day before)
                        $currentOwnerHistory->update(['sale_date' => $newPurchaseDate->copy()->subDay()->toDateString()]);

                        // Create new ownership record
                        PhoneOwnerHistory::create([
                            'phone_id' => $phone->id,
                            'customer_id' => $validatedOwnershipData['new_customer_id'],
                            'purchase_date' => $newPurchaseDate->toDateString(),
                            'sale_date' => null,
                        ]);
                    }
                } else { // No current owner, assign new one
                     PhoneOwnerHistory::create([
                        'phone_id' => $phone->id,
                        'customer_id' => $validatedOwnershipData['new_customer_id'],
                        'purchase_date' => $newPurchaseDate->toDateString(),
                        'sale_date' => null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.phones.index')->with('success', 'Phone updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error updating phone: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone)
    {
        // Database cascade constraints should handle related phone_owner_histories and repairs.
        // If not, manual deletion would be needed here.
        $phone->delete();
        return redirect()->route('admin.phones.index')->with('success', 'Phone deleted successfully.');
    }
}
