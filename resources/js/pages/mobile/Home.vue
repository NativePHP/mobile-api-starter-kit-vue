<script setup lang="ts">
import { secureStorage } from '#nativephp';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { apiFetch } from '@/lib/api';

interface Props {
    userName: string;
    userEmail: string;
}

const props = defineProps<Props>();
const loggingOut = ref(false);

const logout = async () => {
    loggingOut.value = true;

    try {
        const tokenResult = await secureStorage.get('api_token');

        await apiFetch('/api/logout', {
            method: 'POST',
            token: tokenResult?.value ?? undefined,
        });
    } catch (error) {
        console.error(error);
    }

    await secureStorage.delete('api_token');
    await secureStorage.delete('user_name');
    await secureStorage.delete('user_email');

    await fetch('/logout');

    window.location.replace('/');
};
</script>

<template>
    <Head title="Home" />

    <div class="flex flex-col gap-4 ">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                Welcome back!
            </h2>
            <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                {{ props.userName }}
            </p>
            <p class="text-sm text-zinc-500 dark:text-zinc-500">
                {{ props.userEmail }}
            </p>
        </div>

        <div
            class="rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 p-6 text-white shadow-xl"
        >
            <h3 class="text-lg font-semibold">
                Ready to build something amazing?
            </h3>
            <p class="mt-2 text-blue-100">
                Your mobile app is set up with Sanctum authentication, Biometric
                Security, SecureStorage, and EDGE components.
            </p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-800">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                Quick Actions
            </h3>
            <div class="mt-4 flex flex-col gap-3">
                <button
                    type="button"
                    class="w-full rounded-lg bg-red-600 px-4 py-2 text-white disabled:opacity-50"
                    @click="logout"
                    :disabled="loggingOut"
                >
                    {{ loggingOut ? 'Logging out...' : 'Log out' }}
                </button>
            </div>
        </div>
    </div>
</template>
