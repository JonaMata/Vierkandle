<script setup lang="ts">
const props = defineProps<{
    start: DOMRect,
    end: DOMRect,
}>()
</script>

<template>
    <div v-if="start.y == end.y" class="hor connection fixed h-8"
         :style="`top: ${start.top+start.height/2}px; left: calc(${Math.min(start.x+start.width/2, end.x+end.width/2)}px - 1rem); width: calc(${Math.abs(end.x-start.x)}px + 2rem);`">
    </div>
    <div v-else-if="start.x == end.x" class="ver connection fixed w-8"
         :style="`left: ${start.left+start.width/2}px; top: calc(${Math.min(start.y+start.height/2, end.y+end.height/2)}px - 1rem); height: calc(${Math.abs(end.y-start.y)}px + 2rem);`">
    </div>
    <div v-else-if="Math.abs((start.x-end.x) - (start.y-end.y)) < 1" class="connection diag-1 fixed h-8"
         :style="`left: calc(${Math.min(start.x+start.width/2, end.x+end.width/2)}px - 1rem); top: calc(${Math.min(start.y+start.height/2, end.y+end.height/2)}px - 1rem); width: calc(${Math.sqrt(Math.pow(end.x-start.x,2)*2)}px + 2rem);`">
    </div>
    <div v-else class="connection diag-2 fixed h-8"
         :style="`left: calc(${Math.min(start.x+start.width/2, end.x+end.width/2)}px - 1rem); top: calc(${Math.max(start.y+start.height/2, end.y+end.height/2)}px - 1rem); width: calc(${Math.sqrt(Math.pow(end.x-start.x,2)*2)}px + 2rem);`">
    </div>
</template>

<style scoped>
.connection {
    background: red;
    border-radius: 2rem;
}

.hor {
    transform: translate(0, -50%);
}

.ver {
    transform: translate(-50%, 0);
}
.diag-1 {
    transform-origin: left top;
    transform: rotate(45deg) translate(5%, -50%);
}
.diag-2 {
    transform-origin: left top;
    transform: rotate(-45deg) translate(-10%, 20%);
}
</style>
