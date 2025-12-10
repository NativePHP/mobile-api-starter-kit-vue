<script setup lang="ts">
import { dialog, secureStorage } from '#nativephp';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    userName: string;
    userEmail: string;
}

const props = defineProps<Props>();

const profileForm = useForm({
    name: props.userName,
    email: props.userEmail,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatingProfile = ref(false);
const updatingPassword = ref(false);
const loggingOut = ref(false);

const updateProfile = async () => {
    updatingProfile.value = true;
    profileForm.clearErrors();

    try {
        const token = await secureStorage.get('api_token');

        const response = await fetch('/api/user/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                name: profileForm.name,
                email: profileForm.email,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            await secureStorage.set('user_name', data.user.name);
            await secureStorage.set('user_email', data.user.email);

            await dialog.toast('Profile updated successfully');
        } else {
            await dialog.toast(data.message || 'Failed to update profile');

            if (data.errors?.email) {
                profileForm.setError('email', data.errors.email[0]);
            }
        }
    } catch (error) {
        console.error(error);
        await dialog.toast('An error occurred. Please try again.');
    } finally {
        updatingProfile.value = false;
    }
};

const updatePassword = async () => {
    updatingPassword.value = true;
    passwordForm.clearErrors();

    try {
        const token = await secureStorage.get('api_token');

        const response = await fetch('/api/user/password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                current_password: passwordForm.current_password,
                password: passwordForm.password,
                password_confirmation: passwordForm.password_confirmation,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            await dialog.toast('Password updated successfully');

            passwordForm.current_password = '';
            passwordForm.password = '';
            passwordForm.password_confirmation = '';
        } else {
            await dialog.toast(data.message || 'Failed to update password');

            if (data.errors?.current_password) {
                passwordForm.setError(
                    'current_password',
                    data.errors.current_password[0],
                );
            }
        }
    } catch (error) {
        await dialog.toast('An error occurred. Please try again.');
    } finally {
        updatingPassword.value = false;
    }
};

const logout = async () => {
    loggingOut.value = true;

    try {
        const token = await secureStorage.get('api_token');

        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
        });
    } catch (error) {
        // Ignore logout API errors
    }

    await secureStorage.delete('api_token');
    await secureStorage.delete('user_name');
    await secureStorage.delete('user_email');

    await fetch('/logout');

    window.location.replace('/');
};
</script>

<template>
    <Head title="Profile" />

    <div class="flex flex-col gap-4">
        <!-- Profile Information Section -->
        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                Profile Information
            </h2>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Update your account's profile information and email address.
            </p>

            <form
                @submit.prevent="updateProfile"
                class="mt-6 flex flex-col gap-6"
            >
                <div>
                    <label
                        for="name"
                        class="block font-medium text-zinc-900 dark:text-white"
                    >
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="profileForm.name"
                        type="text"
                        class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                        required
                    />
                    <p
                        v-if="profileForm.errors.name"
                        class="mt-1 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ profileForm.errors.name }}
                    </p>
                </div>

                <div>
                    <label
                        for="email"
                        class="block font-medium text-zinc-900 dark:text-white"
                    >
                        Email
                    </label>
                    <input
                        id="email"
                        v-model="profileForm.email"
                        type="email"
                        class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                        required
                    />
                    <p
                        v-if="profileForm.errors.email"
                        class="mt-1 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ profileForm.errors.email }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                    :disabled="updatingProfile"
                >
                    {{ updatingProfile ? 'Updating...' : 'Update Profile' }}
                </button>
            </form>
        </div>

        <!-- Update Password Section -->
        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                Update Password
            </h2>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Ensure your account is using a long, random password to stay
                secure.
            </p>

            <form
                @submit.prevent="updatePassword"
                class="mt-6 flex flex-col gap-6"
            >
                <div>
                    <label
                        for="current_password"
                        class="block font-medium text-zinc-900 dark:text-white"
                    >
                        Current Password
                    </label>
                    <input
                        id="current_password"
                        v-model="passwordForm.current_password"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                        required
                    />
                    <p
                        v-if="passwordForm.errors.current_password"
                        class="mt-1 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ passwordForm.errors.current_password }}
                    </p>
                </div>

                <div>
                    <label
                        for="password"
                        class="block font-medium text-zinc-900 dark:text-white"
                    >
                        New Password
                    </label>
                    <input
                        id="password"
                        v-model="passwordForm.password"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                        required
                    />
                    <p
                        v-if="passwordForm.errors.password"
                        class="mt-1 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ passwordForm.errors.password }}
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
                        v-model="passwordForm.password_confirmation"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                        required
                    />
                    <p
                        v-if="passwordForm.errors.password_confirmation"
                        class="mt-1 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ passwordForm.errors.password_confirmation }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                    :disabled="updatingPassword"
                >
                    {{ updatingPassword ? 'Updating...' : 'Update Password' }}
                </button>
            </form>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-800">
            <button
                type="button"
                class="w-full rounded-lg bg-red-600 px-4 py-2 text-white disabled:opacity-50"
                @click="logout"
                :disabled="loggingOut"
            >
                {{ loggingOut ? 'Logging out...' : 'Logout' }}
            </button>
        </div>
    </div>
</template>
