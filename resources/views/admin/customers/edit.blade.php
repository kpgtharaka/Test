<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Customer') }}: {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                            <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="name" value="{{ old('name', $customer->name) }}" required autofocus />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="email" name="email" value="{{ old('email', $customer->email) }}" required />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone Number') }}</label>
                            <input id="phone_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" required />
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Address') }}</label>
                            <textarea id="address" name="address" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                                {{ __('Update Customer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Owned Phones</h3>
                    @if($customer->phones->count() > 0)
                        <ul class="mt-4 space-y-2">
                            @foreach($customer->phones as $phone)
                                <li class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <p class="font-semibold">{{ $phone->brand }} {{ $phone->model }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">IMEI: {{ $phone->imei }} | Serial: {{ $phone->serial_number }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">
                                        Purchased: {{ $phone->pivot->purchase_date ? \Carbon\Carbon::parse($phone->pivot->purchase_date)->format('M d, Y') : 'N/A' }}
                                        @if($phone->pivot->sale_date)
                                            | Sold: {{ \Carbon\Carbon::parse($phone->pivot->sale_date)->format('M d, Y') }}
                                        @else
                                            | (Currently Owned)
                                        @endif
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-gray-600 dark:text-gray-400">This customer has no phone ownership history recorded.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Repair History</h3>
                     @if($customer->repairs->count() > 0)
                        <ul class="mt-4 space-y-2">
                            @foreach($customer->repairs as $repair)
                                <li class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <p class="font-semibold">Repair ID: {{ $repair->id }} for {{ $repair->phone->brand ?? 'N/A' }} {{ $repair->phone->model ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status: {{ $repair->status }} | Received: {{ \Carbon\Carbon::parse($repair->date_received)->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Problem: {{ Str::limit($repair->description_of_problem, 100) }}</p>
                                    {{-- <a href="{{ route('admin.repairs.show', $repair->id) }}" class="text-indigo-500 hover:underline text-sm">View Details</a> --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-gray-600 dark:text-gray-400">This customer has no repair history recorded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
