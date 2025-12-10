<script setup lang="ts">
import { secureStorage } from '#nativephp';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const errorMessage = ref('');
const processing = ref(false);

const register = async () => {
    processing.value = true;
    errorMessage.value = '';
    form.clearErrors();

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                name: form.name,
                email: form.email,
                password: form.password,
                password_confirmation: form.password_confirmation,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            await secureStorage.set('api_token', data.token);
            await secureStorage.set('user_id', String(data.user.id));
            await secureStorage.set('user_name', data.user.name);
            await secureStorage.set('user_email', data.user.email);

            window.location.replace('/home');
        } else {
            errorMessage.value = data.message || 'Registration failed';

            if (data.errors) {
                if (data.errors.email) {
                    form.setError('email', data.errors.email[0]);
                }
                if (data.errors.name) {
                    form.setError('name', data.errors.name[0]);
                }
                if (data.errors.password) {
                    form.setError('password', data.errors.password[0]);
                }
            }
        }
    } catch (error) {
        errorMessage.value = 'An error occurred. Please try again.';
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <Head title="Register" />

    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Create an account
            </h1>
            <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                Enter your details below to create your account
            </p>
        </div>

        <div
            v-if="errorMessage"
            class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20"
        >
            <p class="text-red-800 dark:text-red-200">{{ errorMessage }}</p>
        </div>

        <form @submit.prevent="register" class="flex flex-col gap-6">
            <div>
                <label
                    for="name"
                    class="block font-medium text-zinc-900 dark:text-white"
                >
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                    placeholder="Your name"
                    required
                    autofocus
                />
                <p
                    v-if="form.errors.name"
                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.name }}
                </p>
            </div>

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

            <div>
                <label
                    for="password_confirmation"
                    class="block font-medium text-zinc-900 dark:text-white"
                >
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                    placeholder="Confirm password"
                    required
                />
                <p
                    v-if="form.errors.password_confirmation"
                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                :disabled="processing"
            >
                {{ processing ? 'Creating Account...' : 'Create Account' }}
            </button>
        </form>

        <div class="text-center text-zinc-600 dark:text-zinc-400">
            <span>Already have an account?</span>
            <Link href="/login" class="ml-1 text-blue-600 dark:text-blue-400">
                Log in
            </Link>
        </div>
    </div>
</template>
