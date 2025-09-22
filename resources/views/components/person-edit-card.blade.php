@props(['person', 'label', 'prefix'])

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    {{-- This is now its own independent form --}}
    <form action="{{ route('photographer.case.person.update', ['case' => $person->pivot->case_id, 'person' => $person->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $label }}</h3>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm font-semibold flex items-center gap-1">
                <i data-feather="save" class="w-4 h-4"></i> Save
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Input names are now simple, without prefixes --}}
            <div><label for="first_name_{{ $person->id }}">First Name</label><input type="text" id="first_name_{{ $person->id }}" name="first_name" value="{{ old('first_name', $person->first_name) }}" class="form-input"></div>
            <div><label for="surname_{{ $person->id }}">Surname</label><input type="text" id="surname_{{ $person->id }}" name="surname" value="{{ old('surname', $person->surname) }}" class="form-input"></div>
            <div><label for="id_number_{{ $person->id }}">National ID Number</label><input type="text" id="id_number_{{ $person->id }}" name="id_number" value="{{ old('id_number', $person->id_number) }}" class="form-input"></div>
            <div><label for="phone_number_{{ $person->id }}">Phone Number</label><input type="text" id="phone_number_{{ $person->id }}" name="phone_number" value="{{ old('phone_number', $person->phone_number) }}" class="form-input"></div>
            <div class="md:col-span-2"><label for="address_{{ $person->id }}">Address</label><input type="text" id="address_{{ $person->id }}" name="address" value="{{ old('address', $person->address) }}" class="form-input"></div>
            <div class="md:col-span-2"><label for="email_{{ $person->id }}">Email (Optional)</label><input type="email" id="email_{{ $person->id }}" name="email" value="{{ old('email', $person->email) }}" class="form-input"></div>
        </div>
    </form>
</div>