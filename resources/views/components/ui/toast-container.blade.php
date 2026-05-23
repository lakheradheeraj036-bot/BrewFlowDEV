<div
    x-data="{ 
        notifications: [],
        add(message, type = 'success', duration = 4000) {
            const id = Date.now();
            this.notifications.push({ id, message, type });
            if (duration) {
                setTimeout(() => this.remove(id), duration);
            }
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="add($event.detail.message, $event.detail.type, $event.detail.duration)"
    class="fixed top-4 right-4 z-50 space-y-3 pointer-events-none">

    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="pointer-events-auto rounded-xl border shadow-lg overflow-hidden"
            :class="{
                'bg-emerald-50 border-emerald-200': notification.type === 'success',
                'bg-red-50 border-red-200': notification.type === 'error',
                'bg-amber-50 border-amber-200': notification.type === 'warning',
                'bg-blue-50 border-blue-200': notification.type === 'info',
            }">

            <div class="flex items-start gap-3 p-4">
                <!-- Icon -->
                <template x-if="notification.type === 'success'">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="notification.type === 'warning'">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="notification.type === 'info'">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>

                <!-- Content -->
                <div class="flex-1">
                    <p class="text-sm font-medium"
                        :class="{
                            'text-emerald-800': notification.type === 'success',
                            'text-red-800': notification.type === 'error',
                            'text-amber-800': notification.type === 'warning',
                            'text-blue-800': notification.type === 'info',
                        }"
                        x-text="notification.message">
                    </p>
                </div>

                <!-- Close button -->
                <button
                    @click="show = false; $dispatch('toast-close', notification.id)"
                    class="text-slate-400 hover:text-slate-600 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress bar -->
            <div class="h-1 w-full"
                :class="{
                    'bg-emerald-200': notification.type === 'success',
                    'bg-red-200': notification.type === 'error',
                    'bg-amber-200': notification.type === 'warning',
                    'bg-blue-200': notification.type === 'info',
                }"
                x-init="
                    let width = 100;
                    const interval = setInterval(() => {
                        width -= 1;
                        $el.style.width = width + '%';
                        if (width <= 0) clearInterval(interval);
                    }, 40);
                ">
            </div>
        </div>
    </template>
</div>