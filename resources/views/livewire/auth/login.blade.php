<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-4">

    {{-- Background blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-500 rounded-2xl mb-4 shadow-lg shadow-amber-500/30">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">BrewFlow</h1>
            <p class="text-slate-400 mt-1 text-sm">Super Admin Portal</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-white">Welcome back</h2>
                <p class="text-slate-400 text-sm mt-1">Sign in to manage your platform</p>
            </div>

            {{-- Server-side error --}}
            @if($errorMessage)
                <div class="mb-5 p-3 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-400 text-sm">{{ $errorMessage }}</p>
                </div>
            @endif

            <form wire:submit="login" class="space-y-5">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                        Email address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            id="email"
                            wire:model="email"
                            type="email"
                            autocomplete="username"
                            placeholder="admin@brewflow.com"
                            class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all text-sm"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                {{--
                    IMPORTANT: Single input, type="password" hardcoded.
                    No Alpine :type binding — that conflicts with wire:model
                    in Livewire 4 and causes the value to be lost on submit.
                --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password"
                            wire:model="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all text-sm"
                        />
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div>
                    <label class="flex items-center gap-2 cursor-pointer group w-fit">
                        <input
                            wire:model="remember"
                            type="checkbox"
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-amber-500 focus:ring-amber-500/30"
                        />
                        <span class="text-sm text-slate-400 group-hover:text-slate-300 transition-colors">
                            Remember me
                        </span>
                    </label>
                </div>

                {{-- Submit button --}}
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-not-allowed"
                    class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-400 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 focus:outline-none focus:ring-2 focus:ring-amber-500/50 flex items-center justify-center gap-2"
                >
                    <svg wire:loading wire:target="login" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="login">Sign in to Dashboard</span>
                    <span wire:loading wire:target="login">Authenticating...</span>
                </button>

            </form>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} BrewFlow. All rights reserved.
        </p>
    </div>
</div>
