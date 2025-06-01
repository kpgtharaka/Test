<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Phone') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.phones.store') }}">
                        @csrf

                        <!-- Brand -->
                        <div class="mt-4">
                            <label for="brand" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Brand') }}</label>
                            <input id="brand" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="brand" value="{{ old('brand') }}" required />
                            @error('brand') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Model -->
                        <div class="mt-4">
                            <label for="model" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Model') }}</label>
                            <input id="model" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="model" value="{{ old('model') }}" required />
                            @error('model') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- IMEI -->
                        <div class="mt-4">
                            <label for="imei" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('IMEI') }}</label>
                            <input id="imei" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="imei" value="{{ old('imei') }}" required />
                            @error('imei') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Serial Number -->
                        <div class="mt-4">
                            <label for="serial_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Serial Number') }}</label>
                            <input id="serial_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="serial_number" value="{{ old('serial_number') }}" required />
                            @error('serial_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <hr class="my-6 border-gray-300 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Initial Owner (Optional)</h3>

                        <!-- Initial Owner -->
                        <div class="mt-4">
                            <label for="customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Assign to Customer') }}</label>
                            <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">{{ __('None') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                            @error('customer_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Purchase Date -->
                        <div class="mt-4">
                            <label for="purchase_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Purchase Date by Customer') }}</label>
                            <input id="purchase_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="date" name="purchase_date" value="{{ old('purchase_date') }}" />
                            @error('purchase_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if assigning to a customer.</p>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.phones.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                                {{ __('Create Phone') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
