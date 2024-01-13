<script setup lang="ts">
defineProps<{
    letter: string,
    start: number,
    includes: number,
    showStart?: boolean,
    showIncludes?: boolean,
}>()
</script>

<template>
<div class="vierkand">
    <div class="letter">
        {{ letter }}
    </div>
    <div v-if="showStart" class="start">
        {{ start }}
    </div>
    <div v-if="showIncludes" class="includes">
        {{ includes }}
    </div>
</div>
</template>

<style scoped>
.vierkand {
    display: inline-block;
    position: relative;
    width: 50px;
    height: 50px;
    background: gray;
    border-radius: 10px;
    margin-right: 5px;
}

.letter {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 200%
}

.start {
    position: absolute;
    left: 10%;
    bottom: 2%;
    color: red;
    font-size: 80%;
}

.includes {
    position: absolute;
    right: 10%;
    bottom: 2%;
    font-size: 80%;
}
</style>
