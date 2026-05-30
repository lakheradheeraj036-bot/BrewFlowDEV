<x-layouts.super-admin title="Add Business">
    <x-super-admin.breadcrumb :items="[
        ['label' => 'Businesses', 'url' => route('super-admin.businesses.index')],
        ['label' => 'Add Business'],
    ]"/>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Add New Business</h2>
        <p class="text-sm text-slate-500 mt-0.5">Register a new business on the BrewFlow platform</p>
    </div>

    <form
        method="POST"
        action="{{ route('super-admin.businesses.store') }}"
        enctype="multipart/form-data"
        x-data="{
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            phone: '{{ old('phone') }}',
            logoPreview: null,
            submitting: false,
            errors: {},
            validate(field) {
                delete this.errors[field]
                if (field === 'name' && this.name.trim().length < 2)
                    this.errors.name = 'Business name must be at least 2 characters.'
                if (field === 'email' && this.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email))
                    this.errors.email = 'Please enter a valid email address.'
                if (field === 'phone' && this.phone && this.phone.length > 20)
                    this.errors.phone = 'Phone number may not exceed 20 characters.'
            },
            handleLogoUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.logoPreview = URL.createObjectURL(file);
                }
            },
            removeLogo() {
                this.logoPreview = null;
                this.$refs.logoInput.value = '';
            },
            submit(e) {
                this.errors = {}
                if (this.name.trim().length < 2) this.errors.name = 'Business name must be at least 2 characters.'
                if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) this.errors.email = 'Business email is required.'
                if (this.phone && this.phone.length > 20) this.errors.phone = 'Phone number may not exceed 20 characters.'
                if (Object.keys(this.errors).length) { e.preventDefault(); return }
                this.submitting = true
            }
        }"
        @submit="submit($event)"
        novalidate
    >
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- Left: Form Cards --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Logo Upload --}}
                <x-ui.card title="Business Logo">
                    <div class="space-y-4">
                        <div
                            class="relative border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer hover:border-amber-400 hover:bg-amber-50"
                            :class="logoPreview ? 'border-amber-400 bg-amber-50' : 'border-slate-300'"
                            @click="$refs.logoInput.click()"
                            @dragover.prevent="$el.classList.add('border-amber-400', 'bg-amber-50')"
                            @dragleave.prevent="$el.classList.remove('border-amber-400', 'bg-amber-50')"
                            @drop.prevent="handleLogoUpload({target: {files: $event.dataTransfer.files}})"
                        >
                            <input
                                type="file"
                                name="logo"
                                x-ref="logoInput"
                                accept="image/jpeg,image/png,image/jpg,image/svg,image/webp"
                                class="hidden"
                                @change="handleLogoUpload($event)"
                            >
                            <template x-if="logoPreview">
                                <div class="space-y-3">
                                    <img :src="logoPreview" alt="Logo preview" class="w-24 h-24 object-contain mx-auto rounded-lg">
                                    <button
                                        type="button"
                                        @click.stop="removeLogo()"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                                    >
                                        Remove Logo
                                    </button>
                                </div>
                            </template>
                            <template x-if="!logoPreview">
                                <div class="space-y-3">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Upload business logo</p>
                                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, SVG or WEBP (max 2MB)</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @error('logo')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.card>

                {{-- Business Information --}}
                <x-ui.card title="Business Information">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Business Name *</label>
                            <input
                                type="text"
                                name="name"
                                x-model="name"
                                @blur="validate('name')"
                                placeholder="e.g. The Daily Grind Cafe"
                                maxlength="100"
                                value="{{ old('name') }}"
                                class="w-full px-3 py-2.5 text-sm border rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                                :class="(errors.name || '{{ $errors->first('name') }}') ? 'border-red-400' : 'border-slate-300'"
                            >
                            <p x-show="errors.name" x-text="errors.name" class="text-xs text-red-500 mt-1.5"></p>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Business Type *</label>
                            <select
                                name="type"
                                class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                            >
                                @foreach(['cafe' => '☕ Cafe', 'hotel' => '🏨 Hotel', 'restaurant' => '🍽️ Restaurant', 'bar' => '🍺 Bar', 'other' => '🏢 Other'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('type', 'cafe') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select
                                name="status"
                                class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                            >
                                @foreach(['pending' => 'Pending', 'active' => 'Active', 'inactive' => 'Inactive'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('status', 'pending') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                            <textarea
                                name="description"
                                rows="3"
                                placeholder="Describe the business..."
                                maxlength="2000"
                                class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all resize-none"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                {{-- Contact Details --}}
                <x-ui.card title="Contact Details">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Business Email *</label>
                            <input
                                type="email"
                                name="email"
                                x-model="email"
                                @blur="validate('email')"
                                placeholder="business@example.com"
                                maxlength="150"
                                value="{{ old('email') }}"
                                class="w-full px-3 py-2.5 text-sm border rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                                :class="(errors.email || '{{ $errors->first('email') }}') ? 'border-red-400' : 'border-slate-300'"
                            >
                            <p x-show="errors.email" x-text="errors.email" class="text-xs text-red-500 mt-1.5"></p>
                            @error('email')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                            <input
                                type="tel"
                                name="phone"
                                x-model="phone"
                                @blur="validate('phone')"
                                placeholder="+91 XXXXX XXXXX"
                                maxlength="20"
                                value="{{ old('phone') }}"
                                class="w-full px-3 py-2.5 text-sm border rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                                :class="(errors.phone || '{{ $errors->first('phone') }}') ? 'border-red-400' : 'border-slate-300'"
                            >
                            <p x-show="errors.phone" x-text="errors.phone" class="text-xs text-red-500 mt-1.5"></p>
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <x-ui.input
                                name="address"
                                label="Address"
                                placeholder="123 Main Street"
                                :value="old('address')"
                                :error="$errors->first('address')"
                            />
                        </div>

                        <x-ui.input
                            name="city"
                            label="City"
                            placeholder="Mumbai"
                            :value="old('city')"
                            :error="$errors->first('city')"
                        />

                        <x-ui.input
                            name="country"
                            label="Country"
                            placeholder="India"
                            :value="old('country', 'India')"
                            :error="$errors->first('country')"
                        />
                    </div>
                </x-ui.card>

                {{-- Subscription Plan --}}
                <x-ui.card title="Subscription Plan">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Select Subscription Plan</label>
                        <select
                            name="subscription_plan_id"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                        >
                            <option value="">Select a plan</option>
                            @foreach($subscriptionPlans as $plan)
                                <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - {{ $plan->formatted_price }}/{{ $plan->interval }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_plan_id')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.card>

            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-6 lg:sticky lg:top-6">

                {{-- Quick Guide --}}
                <x-ui.card title="Quick Guide">
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                            <span>Upload a business logo (optional).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                            <span>Enter business name, type, and description.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                            <span>Provide business email (required) and contact details.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">4</span>
                            <span>Select a subscription plan from available options.</span>
                        </li>
                    </ul>
                </x-ui.card>

                {{-- Status Info --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500 space-y-1.5">
                    <p class="font-medium text-slate-700">Status meanings</p>
                    <p><span class="font-medium text-amber-600">Pending</span> — awaiting review</p>
                    <p><span class="font-medium text-emerald-600">Active</span> — live on the platform</p>
                    <p><span class="font-medium text-slate-500">Inactive</span> — suspended or disabled</p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full justify-center inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl bg-amber-500 text-white hover:bg-amber-600 disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                    >
                        <svg x-show="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitting ? 'Creating...' : 'Create Business'"></span>
                    </button>
                    <x-ui.button href="{{ route('super-admin.businesses.index') }}" variant="outline" class="w-full justify-center">
                        Cancel
                    </x-ui.button>
                </div>

            </div>
        </div>
    </form>
</x-layouts.super-admin>
