<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Repair Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.repairs.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer -->
                            <div>
                                <label for="customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Customer') }}</label>
                                <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->email }})</option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                                <select id="phone_id" name="phone_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">Select Phone</option>
                                    @foreach($phones as $phone)
                                        <option value="{{ $phone->id }}" {{ old('phone_id') == $phone->id ? 'selected' : '' }}>{{ $phone->brand }} {{ $phone->model }} ({{ $phone->imei }})</option>
                                    @endforeach
                                </select>
                                @error('phone_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tip: You can add new phones in the "Phones" section.</p>
                            </div>

                            <!-- Date Received -->
                            <div>
                                <label for="date_received" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date Received') }}</label>
                                <input id="date_received" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="date" name="date_received" value="{{ old('date_received', now()->toDateString()) }}" required />
                                @error('date_received') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Initial Status') }}</label>
                                <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="Received" @if(old('status', 'Received') == 'Received') selected @endif>Received</option>
                                    <option value="Diagnosing" @if(old('status') == 'Diagnosing') selected @endif>Diagnosing</option>
                                    <option value="Pending Parts" @if(old('status') == 'Pending Parts') selected @endif>Pending Parts</option>
                                    <option value="Repairing" @if(old('status') == 'Repairing') selected @endif>Repairing</option>
                                    <option value="Completed" @if(old('status') == 'Completed') selected @endif>Completed</option>
                                    <option value="Cannot Repair" @if(old('status') == 'Cannot Repair') selected @endif>Cannot Repair</option>
                                    <option value="Collected" @if(old('status') == 'Collected') selected @endif>Collected</option>
                                </select>
                                @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Estimated Cost -->
                            <div class="md:col-span-2">
                                <label for="estimated_cost" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Estimated Cost ($)') }}</label>
                                <input id="estimated_cost" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="number" step="0.01" name="estimated_cost" value="{{ old('estimated_cost') }}" />
                                @error('estimated_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description of Problem -->
                            <div class="md:col-span-2">
                                <label for="description_of_problem" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Description of Problem') }}</label>
                                <textarea id="description_of_problem" name="description_of_problem" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>{{ old('description_of_problem') }}</textarea>
                                @error('description_of_problem') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                             <!-- Notes (Technician's internal notes) -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Technician Notes (Optional)') }}</label>
                                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes') }}</textarea>
                                @error('notes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.repairs.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                                {{ __('Create Repair Order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
