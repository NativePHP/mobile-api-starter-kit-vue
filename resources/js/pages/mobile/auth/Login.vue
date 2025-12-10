<script setup lang="ts">
import { secureStorage } from '#nativephp';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email: '',
    password: '',
});

const errorMessage = ref('');
const processing = ref(false);

const login = async () => {
    processing.value = true;
    errorMessage.value = '';

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                email: form.email,
                password: form.password,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            await secureStorage.set('api_token', data.token);
            await secureStorage.set('user_name', data.user.name);
            await secureStorage.set('user_email', data.user.email);

            window.location.replace('/home');
        } else {
            errorMessage.value = data.message || 'Invalid credentials';
        }
    } catch (error) {
        errorMessage.value = 'An error occurred. Please try again.';
    } finally {
        processing.value = false;
    }
};

const skipLogin = async () => {
    processing.value = true;

    await secureStorage.set('api_token', crypto.randomUUID());
    await secureStorage.set('user_name', 'Demo User');
    await secureStorage.set('user_email', 'demo@nativephp.com');

    window.location.replace('/home');
};
</script>

<template>
    <Head title="Log in" />

    <div class="flex flex-col gap-6 p-4">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Log in to your account
            </h1>
            <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                Enter your email and password below to log in
            </p>
            <p
                class="mt-6 rounded-lg bg-blue-50 p-4 text-blue-800 shadow dark:bg-blue-900 dark:text-blue-200"
            >
                This is the NativePHP Mobile API Starter Kit. To get started you
                must connect your app to an API. The API endpoints for this app
                are included in the same codebase. You can simply run `herd
                share` or use something like `ngrok` to start up the server.
                Then update your `.env` with the API endpoint, then continue to
                register/login.
            </p>
        </div>

        <div
            v-if="errorMessage"
            class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20"
        >
            <p class="text-red-800 dark:text-red-200">{{ errorMessage }}</p>
        </div>

        <form @submit.prevent="login" class="flex flex-col gap-6">
            <div>
                <label
                    for="email"
                    class="block font-medium text-zinc-900 dark:text-white"
                >
                    Email address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                    placeholder="email@example.com"
                    required
                    autofocus
                />
                <p
                    v-if="form.errors.email"
                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label
                    for="password"
                    class="block font-medium text-zinc-900 dark:text-white"
                >
                    Password
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                    placeholder="Password"
                    required
                />
                <p
                    v-if="form.errors.password"
                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                :disabled="processing"
            >
                {{ processing ? 'Logging In...' : 'Log In' }}
            </button>

            <button
                type="button"
                class="w-full rounded-lg bg-purple-600 px-4 py-2 text-white"
                @click="skipLogin"
                :disabled="processing"
            >
                Skip Login
            </button>
        </form>

        <div class="text-center text-zinc-600 dark:text-zinc-400">
            <span>Don't have an account?</span>
            <Link
                href="/register"
                class="ml-1 text-blue-600 dark:text-blue-400"
            >
                Sign up
            </Link>
        </div>
    </div>
</template>
