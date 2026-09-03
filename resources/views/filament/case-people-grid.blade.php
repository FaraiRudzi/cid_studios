@php
    $people = $getState() ?? [];
    $people = is_array($people) ? collect($people) : $people;
@endphp

@if($people instanceof \Illuminate\Support\Collection && $people->isEmpty())
    <div class="text-sm text-gray-500">No people involved.</div>
@elseif(is_array($people) && empty($people))
    <div class="text-sm text-gray-500">No people involved.</div>
@else
    <div class="case-people-grid">
        @foreach($people as $person)
            @php
                $pivot = $person->pivot ?? null;
                $role = $pivot && $pivot->role ? ucfirst($pivot->role) : 'Person';
            @endphp
            <div class="case-people-card">
                <div class="case-people-header">
                    <div class="case-people-name">
                        {{ trim(($person->first_name ?? '') . ' ' . ($person->surname ?? '')) ?: 'Unnamed person' }}
                    </div>
                    <span class="case-role-badge">{{ $role }}</span>
                </div>

                <dl class="case-people-meta">
                    @if($person->id_number)
                        <div><dt>ID Number</dt><dd>{{ $person->id_number }}</dd></div>
                    @endif
                    @if($person->gender)
                        <div><dt>Gender</dt><dd>{{ $person->gender }}</dd></div>
                    @endif
                    @if($person->phone_number)
                        <div><dt>Phone</dt><dd>{{ $person->phone_number }}</dd></div>
                    @endif
                    @if($person->email)
                        <div><dt>Email</dt><dd>{{ $person->email }}</dd></div>
                    @endif
                    @if($person->address)
                        <div><dt>Address</dt><dd>{{ $person->address }}</dd></div>
                    @endif
                </dl>

                @if($pivot && $pivot->notes)
                    <div class="case-people-notes">
                        <div class="case-notes-label">Notes</div>
                        <p>{{ $pivot->notes }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
