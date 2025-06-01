<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Phone') }}: {{ $phone->brand }} {{ $phone->model }} ({{$phone->imei}})
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

            <!-- Edit Phone Details Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Phone Details</h3>
                    <form method="POST" action="{{ route('admin.phones.update', $phone) }}">
                        @csrf
                        @method('PUT')

                        <!-- Brand -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="brand" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Brand') }}</label>
                                <input id="brand" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="brand" value="{{ old('brand', $phone->brand) }}" required />
                                @error('brand') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Model -->
                            <div>
                                <label for="model" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Model') }}</label>
                                <input id="model" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="model" value="{{ old('model', $phone->model) }}" required />
                                @error('model') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- IMEI -->
                            <div>
                                <label for="imei" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('IMEI') }}</label>
                                <input id="imei" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="imei" value="{{ old('imei', $phone->imei) }}" required />
                                @error('imei') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Serial Number -->
                            <div>
                                <label for="serial_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Serial Number') }}</label>
                                <input id="serial_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="serial_number" value="{{ old('serial_number', $phone->serial_number) }}" required />
                                @error('serial_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <hr class="my-6 border-gray-300 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Manage Ownership</h3>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Owner:
                            <strong>{{ $phone->currentOwner->first()->customer->name ?? 'Unassigned' }}</strong>
                            @if($phone->currentOwner->first())
                                (Since: {{ Carbon\Carbon::parse($phone->currentOwner->first()->purchase_date)->format('M d, Y') }})
                            @endif
                        </p>

                        @if($phone->currentOwner->first())
                        <div class="mb-4">
                             <input type="hidden" name="clear_current_owner" value="0"> <!-- Default value -->
                             <label for="clear_owner_checkbox" class="inline-flex items-center">
                                <input type="checkbox" id="clear_owner_checkbox" name="clear_current_owner" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Make phone unassigned (mark current owner as sold)') }}</span>
                            </label>
                        </div>
                        @endif


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="new_customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Assign/Change to New Customer') }}</label>
                                <select id="new_customer_id" name="new_customer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">{{ __('Select New Owner (Optional)') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('new_customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('new_customer_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="new_purchase_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('New Purchase Date by Customer') }}</label>
                                <input id="new_purchase_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="date" name="new_purchase_date" value="{{ old('new_purchase_date') }}" />
                                @error('new_purchase_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if assigning to a new customer.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.phones.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                                {{ __('Update Phone & Ownership') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ownership History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Ownership History</h3>
                    @if($phone->ownershipHistory->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($phone->ownershipHistory->sortByDesc('purchase_date') as $history)
                                <li class="py-3">
                                    <p class="font-semibold">{{ $history->customer->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Purchased: {{ Carbon\Carbon::parse($history->purchase_date)->format('M d, Y') }}
                                        @if($history->sale_date)
                                            | Sold: {{ Carbon\Carbon::parse($history->sale_date)->format('M d, Y') }}
                                        @else
                                            <span class="font-bold text-green-600 dark:text-green-400">(Current Owner)</span>
                                        @endif
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No ownership history recorded for this phone.</p>
                    @endif
                </div>
            </div>

            <!-- Repair History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Repair History</h3>
                    @if($phone->repairHistory->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($phone->repairHistory as $repair)
                                <li class="py-3">
                                    <p class="font-semibold">Repair ID: {{ $repair->id }} (Customer: {{ $repair->customer->name ?? 'N/A' }})</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status: {{ $repair->status }} | Received: {{ Carbon\Carbon::parse($repair->date_received)->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Problem: {{ Str::limit($repair->description_of_problem, 100) }}</p>
                                    {{-- <a href="{{ route('admin.repairs.show', $repair->id) }}" class="text-indigo-500 hover:underline text-sm">View Repair Details</a> --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No repair history recorded for this phone.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
