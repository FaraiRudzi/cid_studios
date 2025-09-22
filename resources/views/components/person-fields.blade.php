@props([
    'prefix', // This will be 'informant', 'deceased', 'accused', etc.
    'label',  // The heading for this section, e.g., "Informant Details"
    'person' => null, // This will be the existing Person object, or null on the create page
])

<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2 pt-4">{{ $label }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="{{ $prefix }}_first_name" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            {{-- THE FIX IS HERE: It now checks for old input first, then falls back to the existing $person object --}}
            <input type="text" id="{{ $prefix }}_first_name" name="{{ $prefix }}_first_name" value="{{ old($prefix.'_first_name', $person?->first_name) }}" class="form-input">
        </div>
        <div>
            <label for="{{ $prefix }}_surname" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Surname</label>
            <input type="text" id="{{ $prefix }}_surname" name="{{ $prefix }}_surname" value="{{ old($prefix.'_surname', $person?->surname) }}" class="form-input">
        </div>
        <div>
            <label for="{{ $prefix }}_id_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">National ID Number</label>
            <input type="text" id="{{ $prefix }}_id_number" name="{{ $prefix }}_id_number" value="{{ old($prefix.'_id_number', $person?->id_number) }}" class="form-input" placeholder="Used to find or update people">
        </div>
        <div>
            <label for="{{ $prefix }}_phone_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
            <input type="text" id="{{ $prefix }}_phone_number" name="{{ $prefix }}_phone_number" value="{{ old($prefix.'_phone_number', $person?->phone_number) }}" class="form-input">
        </div>

        <div class="md:col-span-2">
            <label for="{{ $prefix }}_address" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
           <textarea id="{{ $prefix }}_address" name="{{ $prefix }}_address" rows="2" class="form-input w-full">{{ old($prefix.'_address', $person?->address) }}</textarea>

        </div>

         <div class="md:col-span-2">
            <label for="{{ $prefix }}_email" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Email (Optional)</label>
            <input type="email" id="{{ $prefix }}_email" name="{{ $prefix }}_email" value="{{ old($prefix.'_email', $person?->email) }}" class="form-input">
        </div>
    </div>
</div>
