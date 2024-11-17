<script setup lang="ts">
import {inject, provide, Ref, ref} from "vue";
import {TabInfo} from "@/Components/Tabs/TabInfo.interface";

const props = defineProps<{
    title: string,
    clickArgument?: any
}>()

const isActive = ref<boolean>(false);

inject<Ref<TabInfo[]>>('Tabs')?.value.push({title: props.title, clickArgument: props.clickArgument, isActive: isActive});
</script>

<template>
<div v-show="isActive">
    <slot>{{ title }}</slot>
</div>
</template>

<style scoped>

</style>
