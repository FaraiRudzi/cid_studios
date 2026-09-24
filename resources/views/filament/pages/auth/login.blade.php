<div class="cid-login min-h-screen w-full bg-[#07182f] text-white">
    <div class="grid min-h-screen w-full grid-cols-1 lg:grid-cols-2">

        {{-- LEFT SIDE - CID STUDIOS BRANDING --}}
        <section class="relative hidden min-h-screen overflow-hidden bg-cover bg-center lg:flex" style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
            {{-- Dark overlay --}}
            <div class="absolute inset-0 bg-[#020b18]/60"></div>

            {{-- Gradient --}}
            <div class="absolute inset-0 bg-gradient-to-br from-[#07182f]/70 via-[#07182f]/50 to-black/90"></div>

            <div class="relative z-10 flex min-h-screen w-full flex-col justify-between p-10 xl:p-14">
                {{-- Top Spacing Placeholder --}}
                <div></div>

                {{-- Centre Content --}}
                <div class="flex flex-col items-center justify-center text-center">
                    {{-- Badge --}}
                    <div class="mb-8 overflow-hidden rounded-lg border-2 border-[#d4af37]/80 bg-white/95 p-3 shadow-2xl">
                        <img src="{{ asset('badge.jpeg') }}" alt="Zimbabwe Republic Police" class="h-auto w-64 object-contain xl:w-72">
                    </div>

                    <h1 class="text-4xl font-bold tracking-tight text-[#d4af37] xl:text-5xl">
                        CID STUDIOS
                    </h1>

                    <div class="my-5 h-px w-20 bg-[#d4af37]"></div>

                    <p class="max-w-md text-sm leading-7 text-gray-300 xl:text-base">
                        Professional photography, forensic media and case documentation management system.
                    </p>

                    <div class="mt-7 rounded-full border border-white/10 bg-black/20 px-5 py-2.5 backdrop-blur-sm">
                        <span class="text-xs font-medium text-gray-300">
                            Authorised Personnel Only
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="border-t border-white/10 pt-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            &copy; {{ date('Y') }} CID Studios
                        </span>
                        <span class="text-xs text-gray-600">
                            Secure Authentication
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- RIGHT SIDE - LOGIN PANEL --}}
        <section class="flex min-h-screen items-center justify-center bg-[#f4f7fb] px-6 py-12 sm:px-10 lg:px-14 xl:px-20">
            <div class="w-full max-w-md">

                {{-- Mobile adaptive branding header --}}
                <div class="mb-10 text-center lg:hidden">
                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full border-2 border-[#d4af37] bg-gray-900 p-1 shadow-xl">
                        <img src="{{ asset('badge.jpeg') }}" alt="CID Studios" class="h-full w-full rounded-full object-cover">
                    </div>
                    <h1 class="text-2xl font-bold text-[#d4af37]">
                        CID Studios
                    </h1>
                    <p class="mt-2 text-xs text-gray-500">
                        Secure Case Media Management Portal
                    </p>
                </div>

                {{-- Login card --}}
                <div class="rounded-2xl border border-[#dbe4ee] bg-white p-7 shadow-xl sm:p-9">
                    {{-- Header (Changed text-white to dark text to stand out cleanly against white card background) --}}
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold tracking-tight text-[#07182f]">
                             Sign in to CID Studios.
                        </h2>
                    </div>

                    {{-- Login form engine configured for Filament v5 --}}
                    <form wire:submit.prevent="authenticate" class="space-y-6">
                        {{-- Injects native username/password elements dynamically --}}
                        {{ $this->form }}

                        <div class="pt-2">
                            <x-filament::actions
                                :actions="$this->getFormActions()"
                                alignment="start"
                                full-width
                            />
                        </div>
                    </form>

                    {{-- Security compliance footer --}}
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <p class="text-xs leading-5 text-gray-400">
                            This system is restricted to authorised CID Studios personnel. System access and activities may be recorded for security and audit purposes.
                        </p>
                    </div>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">
                    CID Studios &bull; Secure Forensic Media Management
                </p>

            </div>
        </section>

    </div>
</div>
