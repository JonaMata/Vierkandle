<script setup lang="ts">
const props = defineProps<{
    title?: string,
    routeName?: string,
    vierkandle: {
        id: number,
        date: string,
        letters: string,
        solution_count: number,
    },
}>()

const solutionsFound = JSON.parse(localStorage.getItem('vierkandle_'+props.vierkandle.id) ?? '[]').length;
</script>

<template>
    <a class="relative hover:opacity-70" :href="routeName ? route(routeName) : route('show', {vierkandle: vierkandle.id})">
        <div class="flex flex-col items-center rounded-lg px-2 pb-2 bg-gray-200 dark:bg-gray-600"
            :class="solutionsFound == vierkandle.solution_count ? 'opacity-30' : ''">
            <div>{{ title ?? vierkandle.date }}</div>
            <div class="grid grid-cols-4 grid-rows-4 gap-1">
                <div v-for="letter in vierkandle.letters" class="w-4 h-4 text-xs rounded bg-gray-300 dark:bg-gray-700 align-middle text-center">{{ letter }}</div>
            </div>
            <div
                class="w-full h-4 rounded overflow-hidden border border-black dark:border-white mt-2 relative">
                <div class="absolute h-full bg-red-500 transition-all"
                     :style="`width: ${solutionsFound/vierkandle.solution_count*100}%`"></div>
            </div>
        </div>
        <div v-if="solutionsFound == vierkandle.solution_count" class="absolute text-4xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">✅</div>
    </a>
</template>

<style scoped>

</style>
