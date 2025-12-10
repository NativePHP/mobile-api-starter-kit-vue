<script setup lang="ts">
import { biometric, Events, off, on } from '#nativephp';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

interface Props {
    isSecure: boolean;
}

const props = defineProps<Props>();
const checking = ref(true);

const handleBiometricComplete = (success: boolean) => {
    if (success) {
        router.visit('/home');
    } else {
        checking.value = false;
    }
};

onMounted(async () => {
    if (props.isSecure) {
        on(Events.Biometric.Completed, handleBiometricComplete);
        await biometric.prompt();
    } else {
        router.visit('/login');
    }
});

onUnmounted(() => {
    off(Events.Biometric.Completed, handleBiometricComplete);
});

const retry = async () => {
    checking.value = true;
    await biometric.prompt();
};
</script>

<template>
    <Head title="Authentication" />

    <div class="flex min-h-screen flex-col items-center justify-center gap-6 ">
        <div v-if="checking" class="text-center">
            <div
                class="mx-auto h-16 w-16 animate-pulse rounded-full bg-blue-100 dark:bg-blue-900/30"
            ></div>
            <p class="mt-4 text-zinc-600 dark:text-zinc-400">
                Authenticating...
            </p>
        </div>

        <div v-else class="text-center">
            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30"
            >
                <svg
                    class="h-8 w-8 text-red-600 dark:text-red-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
            </div>
            <p class="mt-4 text-zinc-900 dark:text-white">
                Authentication failed
            </p>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Please try again
            </p>
            <button
                @click="retry"
                class="mt-6 rounded-lg bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700"
            >
                Try Again
            </button>
        </div>
    </div>
</template>
