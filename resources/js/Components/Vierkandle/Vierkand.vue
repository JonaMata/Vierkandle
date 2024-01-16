<script setup lang="ts">
defineProps<{
    active?: boolean,
    letter: string,
    start: number,
    includes: number,
    showStart?: boolean,
    showIncludes?: boolean,
    isStart?: boolean,
}>()
</script>

<template>
<div class="relative rounded-lg vierkand transition-all"
     :class="`${(start == 0 && includes == 0) ? 'used' : ''} ${active ? 'shadow-2xl border-4 border-red-500 bg-transparent' : 'bg-gray-300 dark:bg-gray-900'}`">
    <div class="letter">
        {{ letter }}
    </div>
    <div v-if="showStart" class="start font-semibold">
        {{ start }}
    </div>
    <div v-if="showIncludes" class="includes font-semibold text-gray-500">
        {{ includes }}
    </div>
<!--    <div v-if="isStart" class="selected"></div>-->
</div>
</template>

<style scoped>
.vierkand {
    width: min(20svw, 10svh);
    height: min(20svw, 10svh);
}

.used {
    opacity: .3;
}

.letter {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 300%
}

.start {
    position: absolute;
    left: 10%;
    bottom: 2%;
    color: red;
    font-size: 90%;
}

.includes {
    position: absolute;
    right: 10%;
    bottom: 2%;
    font-size: 90%;
}

.selected {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    height: 70%;
    border-radius: 50%;
    background: red;
    opacity: .5;
}
</style>
