<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps<{
    links: any[],
}>()
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="'div-'+key" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border dark:border-gray-500 rounded" v-html="link.label" />
                <Link v-else :key="'link-'+key" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border dark:border-gray-500 rounded bg-gray-100 dark:bg-gray-900 hover:bg-white dark:hover:bg-gray-700 focus:border-red-500 dark:focus:border-red-500 focus:text-red-500" :class="{ 'bg-white dark:bg-gray-700 border-red-500 dark:border-red-500': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<style scoped>

</style>
