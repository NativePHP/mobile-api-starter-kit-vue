<script setup lang="ts">
import { biometric, Events, off, on } from '#nativephp';
import { Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const checking = ref(false);
const ready = ref(true);

const handleBiometricComplete = (payload: { success: boolean }) => {
    if (payload.success) {
        window.location.href = '/home';
    } else {
        checking.value = false;
        ready.value = true;
    }
};

const promptForBiometricID = async () => {
    checking.value = true;
    ready.value = false;
    await biometric.prompt();
};

onMounted(() => {
    on(Events.Biometric.Completed, handleBiometricComplete);
});

onUnmounted(() => {
    off(Events.Biometric.Completed, handleBiometricComplete);
});
</script>

<template>
    <Head title="Authentication" />

    <div class="fixed inset-0 flex flex-col items-center justify-center gap-6 bg-white dark:bg-zinc-900">
        <!-- Ready state: prompt user to tap -->
        <div v-if="ready && !checking" class="text-center">
            <button
                @click="promptForBiometricID"
                class="mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-blue-100 transition-colors hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50"
            >
                <svg class="h-16 w-16 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
            </button>
            <p class="mt-4 text-zinc-900 dark:text-white">Tap to authenticate</p>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Use Face ID or fingerprint</p>
        </div>

        <!-- Checking state: waiting for biometric -->
        <div v-else-if="checking" class="text-center">
            <div class="mx-auto flex h-32 w-32 animate-pulse items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                <svg class="h-16 w-16 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
            </div>
            <p class="mt-4 text-zinc-600 dark:text-zinc-400">Authenticating...</p>
        </div>

        <!-- Failed state -->
        <div v-else class="text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="mt-4 text-zinc-900 dark:text-white">Authentication failed</p>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Please try again</p>
            <button
                @click="promptForBiometricID"
                class="mt-6 rounded-lg bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700"
            >
                Try Again
            </button>
        </div>
    </div>
</template>
