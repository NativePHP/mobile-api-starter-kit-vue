<script setup lang="ts">
import { browser } from '#nativephp';
import { formatDistanceToNow } from '@/lib/utils';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Article {
    title: string;
    link: string;
    description: string;
    pubDate: string;
    image: string | null;
}

const articles = ref<Article[]>([]);
const loading = ref(true);

const openArticle = async (url: string) => {
    await browser.inApp(url);
};

onMounted(async () => {
    try {
        const response = await fetch('/news/feed');
        articles.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch articles:', error);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <Head title="Laravel News" />

    <div>
        <!-- Shimmer loading skeleton -->
        <div v-if="loading" class="space-y-4">
            <div
                v-for="i in 5"
                :key="i"
                class="overflow-hidden rounded-lg border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-800"
            >
                <!-- Image placeholder -->
                <div
                    class="h-48 w-full animate-pulse bg-zinc-200 dark:bg-zinc-700"
                ></div>
                <div class="p-4">
                    <!-- Title placeholder -->
                    <div
                        class="mb-2 h-5 w-3/4 animate-pulse rounded bg-zinc-200 dark:bg-zinc-700"
                    ></div>
                    <!-- Description placeholder -->
                    <div
                        class="mb-2 h-4 w-full animate-pulse rounded bg-zinc-200 dark:bg-zinc-700"
                    ></div>
                    <div
                        class="mb-3 h-4 w-2/3 animate-pulse rounded bg-zinc-200 dark:bg-zinc-700"
                    ></div>
                    <!-- Date placeholder -->
                    <div
                        class="h-3 w-24 animate-pulse rounded bg-zinc-200 dark:bg-zinc-700"
                    ></div>
                </div>
            </div>
        </div>

        <div v-else-if="articles.length === 0" class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                No articles available at the moment.
            </p>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="article in articles"
                :key="article.link"
                @click="openArticle(article.link)"
                class="block cursor-pointer overflow-hidden rounded-lg border border-zinc-200 bg-white transition dark:border-zinc-800 dark:bg-zinc-800"
            >
                <img
                    v-if="article.image"
                    :src="article.image"
                    :alt="article.title"
                    class="h-48 w-full object-cover"
                />
                <div class="p-4">
                    <h3
                        class="mb-2 font-semibold text-zinc-900 dark:text-white"
                    >
                        {{ article.title }}
                    </h3>
                    <p
                        class="mb-3 line-clamp-2 text-sm text-zinc-600 dark:text-zinc-400"
                    >
                        {{ article.description }}
                    </p>
                    <time class="text-xs text-zinc-500 dark:text-zinc-500">
                        {{ formatDistanceToNow(article.pubDate) }}
                    </time>
                </div>
            </div>
        </div>
    </div>
</template>
