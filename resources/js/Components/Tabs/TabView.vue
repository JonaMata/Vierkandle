<script setup lang="ts">
import {onMounted, provide, ref} from "vue";
import {TabInfo} from "@/Components/Tabs/TabInfo.interface";
import {s} from "../../../../public/build/assets/app-TZd7Wscu";
import {router} from "@inertiajs/vue3";

const props = defineProps<{
    clickAction?: Function,
    initialTab?: string,
}>();

const selectedTab = ref<number>(0);
const tabs = ref<TabInfo[]>([]);

provide('Tabs', tabs);

const selectTab = (i: number) => {
    selectedTab.value = i
    tabs.value.forEach((tab, index) => {
        tab.isActive = (index === i)
    })
}

const tabClicked = (i: number) => {
    if (props.clickAction) {
        props.clickAction(tabs.value[i].clickArgument);
    } else {
        selectTab(i);
    }
}

onMounted(() => {
    if(props.initialTab) {
        tabs.value.forEach((tab, index) => {
            if (tab.clickArgument == props.initialTab) {
                selectTab(index);
            }
        })
    } else {
        selectTab(0);
    }
})

</script>

<template>
<ul class="flex mb-2 relative gap-3 after:content-[''] after:border-b after:border-gray-300 dark:after:border-gray-600 after:absolute after:w-full after:bottom-0 after:z-10">
    <li v-for="(tab, i) of tabs"
        :key="tab.title"
        @click="() => tabClicked(i)"
        class="cursor-pointer px-2 py-1 rounded-t-md border-x border-t dark:border-gray-600"
        :class="{
            'bg-gray-200 hover:bg-gray-300 dark:bg-gray-900 dark:hover:bg-gray-700': selectedTab !== i,
            'z-20 border-b border-b-white dark:border-b-gray-800': selectedTab === i,
        }"
    >{{ tab.title }}</li>
</ul>
    <slot></slot>
</template>

<style scoped>

</style>
