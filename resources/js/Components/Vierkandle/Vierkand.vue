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
     :class="`${(start == 0 && includes == 0) ? 'used' : ''} ${active ? 'shadow-2xl border-4 border-red-500 border-red-500 bg-transparent' : 'bg-gray-300 dark:bg-gray-900'}`">
    <div class="letter">
        {{ letter }}
    </div>
    <div v-if="showStart" class="start font-semibold">
        {{ start }}
    </div>
    <div v-if="showIncludes" class="includes font-semibold text-gray-500">
        {{ includes }}
    </div>
    <div class="hitbox"></div>
</div>
</template>

<style scoped>
.vierkand {
    --size: min(20svw, 10svh);
    width: var(--size);
    height: var(--size);
    font-size: calc(var(--size)/10);
}

.hitbox {
    position: absolute;
    width: calc(var(--size) * .8);
    height: calc(var(--size) * .8);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    background: transparent;
}

.used {
    opacity: .3;
}

.letter {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 7em;
    text-align: center;
}

.start {
    position: absolute;
    left: 10%;
    bottom: 2%;
    color: red;
    font-size: 1.7em;
}

.includes {
    position: absolute;
    right: 10%;
    bottom: 2%;
    font-size: 1.7em;
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
