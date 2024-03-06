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
     :class="{
        'used': (start == 0 && includes == 0),
        'shadow-2xl border-4 border-red-500 bg-transparent': active,
        'bg-gray-300 dark:bg-gray-900': !active
     }">
    <div class="letter">
        <svg viewBox="0 0 100 100" width="100%" height="100%">
            <text x="50%" y="60%" alignment-baseline="middle" dominant-baseline="middle" text-anchor="middle" font-size="90">{{ letter }}</text>
        </svg>
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
    aspect-ratio: 1;
    --size: 600%;/*min(20svw, 10svh);*/
    /*width: 100%;*/
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
    width: 80%;
    height: 80%;
    transform: translate(-50%, -50%);
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
