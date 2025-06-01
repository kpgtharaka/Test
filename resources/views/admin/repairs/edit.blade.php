<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Repair Order') }} #{{ $repair->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-700 text-green-700 dark:text-green-100 rounded-md shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-700 text-red-700 dark:text-red-100 rounded-md shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Edit Repair Details Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Repair Details</h3>
                    <form method="POST" action="{{ route('admin.repairs.update', $repair) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer (Readonly or selector if you allow changing) -->
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Customer') }}</label>
                                <p class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">{{ $repair->customer->name }} ({{$repair->customer->email}})</p>
                                <input type="hidden" name="customer_id" value="{{ $repair->customer_id }}">
                            </div>

                            <!-- Phone (Readonly or selector) -->
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                                <p class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">{{ $repair->phone->brand }} {{ $repair->phone->model }} (IMEI: {{ $repair->phone->imei }})</p>
                                <input type="hidden" name="phone_id" value="{{ $repair->phone_id }}">
                            </div>

                            <!-- Date Received -->
                            <div>
                                <label for="date_received" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date Received') }}</label>
                                <input id="date_received" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="date" name="date_received" value="{{ old('date_received', $repair->date_received) }}" required />
                                @error('date_received') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Date Completed -->
                            <div>
                                <label for="date_completed" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date Completed') }}</label>
                                <input id="date_completed" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="date" name="date_completed" value="{{ old('date_completed', $repair->date_completed) }}" />
                                @error('date_completed') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                                <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    @foreach(['Received', 'Diagnosing', 'Pending Parts', 'Repairing', 'Completed', 'Cannot Repair', 'Collected'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $repair->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Estimated Cost -->
                            <div>
                                <label for="estimated_cost" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Estimated Cost ($)') }}</label>
                                <input id="estimated_cost" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="number" step="0.01" name="estimated_cost" value="{{ old('estimated_cost', $repair->estimated_cost) }}" />
                                @error('estimated_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Final Cost -->
                            <div class="md:col-span-2">
                                <label for="final_cost" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Final Cost ($)') }}</label>
                                <input id="final_cost" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="number" step="0.01" name="final_cost" value="{{ old('final_cost', $repair->final_cost) }}" />
                                @error('final_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description of Problem -->
                            <div class="md:col-span-2">
                                <label for="description_of_problem" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Description of Problem') }}</label>
                                <textarea id="description_of_problem" name="description_of_problem" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>{{ old('description_of_problem', $repair->description_of_problem) }}</textarea>
                                @error('description_of_problem') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                             <!-- Notes (Technician's internal notes) -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Technician Notes') }}</label>
                                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes', $repair->notes) }}</textarea>
                                @error('notes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.repairs.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                                {{ __('Update Repair Order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Manage Parts Used -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Manage Parts Used</h3>

                    <!-- Form to Add Part -->
                    <form method="POST" action="{{ route('admin.repairs.addPart', $repair) }}" class="mb-6 p-4 border dark:border-gray-700 rounded-md">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="repair_part_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Select Part') }}</label>
                                <select id="repair_part_id" name="repair_part_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Select Part --</option>
                                    @foreach($repairParts as $part)
                                        <option value="{{ $part->id }}" {{ old('repair_part_id') == $part->id ? 'selected' : '' }} {{ $part->stock_quantity <= 0 ? 'disabled' : '' }}>
                                            {{ $part->name }} (SKU: {{ $part->sku }}) - Stock: {{ $part->stock_quantity }}
                                            {{ $part->stock_quantity <= 0 ? '(Out of stock)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('repair_part_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="quantity_used" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Quantity Used') }}</label>
                                <input type="number" id="quantity_used" name="quantity_used" value="{{ old('quantity_used', 1) }}" min="1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                @error('quantity_used') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="self-end">
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-sm">Add Part</button>
                            </div>
                        </div>
                    </form>

                    <!-- List of Currently Added Parts -->
                    <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">Added Parts:</h4>
                    @if($repair->jobParts && $repair->jobParts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Part Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qty Used</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price at Repair</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($repair->jobParts as $jobPart)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $jobPart->repairPart->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $jobPart->repairPart->sku ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $jobPart->quantity_used }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">${{ number_format($jobPart->price_at_time_of_repair, 2) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-sm">
                                        <form action="{{ route('admin.repairs.removePart', ['repair' => $repair, 'repairJobPart' => $jobPart]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this part? Stock will be reverted.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-300">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No parts added to this repair yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
