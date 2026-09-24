<div class="cid-login min-h-screen w-full bg-[#f4f7fb] text-gray-900 flex items-center justify-center px-6 py-12 sm:px-10 lg:px-14 xl:px-20">
    <div class="w-full max-w-md">

       
        {{-- Form Card --}}
        <div class="rounded-2xl border border-[#dbe4ee] bg-white p-7 shadow-xl sm:p-9">
            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-[#07182f]">
                    Reset Password
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Enter your email address to receive instructions on how to reset your password.
                </p>
            </div>

            {{-- Form engine configured for Filament v5 --}}
            <form wire:submit.prevent="request" class="space-y-6">
                {{-- Injects native email input fields dynamically --}}
                {{ $this->form }}

                <div class="pt-2">
                    <x-filament::actions
                        :actions="$this->getFormActions()"
                        alignment="start"
                        full-width
                    />
                </div>
            </form>

            {{-- Link back to login screen --}}
            <div class="mt-6 text-center text-sm">
                <a href="{{ route('filament.admin.auth.login') }}" class="font-medium text-[#07182f] hover:underline">
                    &larr; Back to login
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} CID Studios &bull; Secure Forensic Media Management
        </p>

    </div>
</div>
