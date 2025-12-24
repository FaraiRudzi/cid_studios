@props([
    'prefix',
    'label',
    'person' => null,
])

<div class="space-y-6">
    {{-- Section Title with Gold Accent --}}
    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3 mb-6">
        <div class="h-8 w-1.5 bg-zrp-gold rounded-full"></div>
        <h3 class="text-lg font-black text-zrp-blue dark:text-gray-200 uppercase tracking-tight">{{ $label }}</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        {{-- First Name --}}
        <div class="space-y-1.5">
            <label for="{{ $prefix }}_first_name" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">First Name</label>
            <input type="text" id="{{ $prefix }}_first_name" name="{{ $prefix }}_first_name"
                   value="{{ old($prefix.'_first_name', $person?->first_name) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none placeholder:font-normal"
                   placeholder="Enter first name">
        </div>

        {{-- Surname --}}
        <div class="space-y-1.5">
            <label for="{{ $prefix }}_surname" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Surname</label>
            <input type="text" id="{{ $prefix }}_surname" name="{{ $prefix }}_surname"
                   value="{{ old($prefix.'_surname', $person?->surname) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none"
                   placeholder="Enter surname">
        </div>

        {{-- National ID --}}
        <div class="space-y-1.5">
            <label for="{{ $prefix }}_id_number" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">National ID Number</label>
            <input type="text" id="{{ $prefix }}_id_number" name="{{ $prefix }}_id_number"
                   value="{{ old($prefix.'_id_number', $person?->id_number) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none"
                   placeholder="00-000000X00">
        </div>

        {{-- Phone Number --}}
        <div class="space-y-1.5">
            <label for="{{ $prefix }}_phone_number" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Phone Number</label>
            <input type="text" id="{{ $prefix }}_phone_number" name="{{ $prefix }}_phone_number"
                   value="{{ old($prefix.'_phone_number', $person?->phone_number) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none"
                   placeholder="+263 ...">
        </div>

        {{-- Address --}}
        <div class="md:col-span-2 space-y-1.5">
            <label for="{{ $prefix }}_address" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Physical Address</label>
            <textarea id="{{ $prefix }}_address" name="{{ $prefix }}_address" rows="2"
                      class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none"
                      placeholder="House number, Street, Suburb, City">{{ old($prefix.'_address', $person?->address) }}</textarea>
        </div>

        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="{{ $prefix }}_email" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Email (Optional)</label>
            <input type="email" id="{{ $prefix }}_email" name="{{ $prefix }}_email"
                   value="{{ old($prefix.'_email', $person?->email) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-zrp-gold focus:ring-4 focus:ring-zrp-gold/10 transition-all outline-none"
                   placeholder="example@mail.com">
        </div>
    </div>
</div>
