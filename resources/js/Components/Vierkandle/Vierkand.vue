<script setup lang="ts">
defineProps<{
    letter: string,
    start: number,
    includes: number,
    showStart?: boolean,
    showIncludes?: boolean,
    isStart?: boolean,
}>()
</script>

<template>
<div class="vierkand" :class="(start == 0 && includes == 0) ? 'used' : ''">
    <div class="letter">
        {{ letter }}
    </div>
    <div v-if="showStart" class="start">
        {{ start }}
    </div>
    <div v-if="showIncludes" class="includes">
        {{ includes }}
    </div>
    <div v-if="isStart" class="selected"></div>
</div>
</template>

<style scoped>
.vierkand {
    position: relative;
    width: min(20svw, 10svh);
    height: min(20svw, 10svh);
    background: lightgray;
    border-radius: 10px;
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
