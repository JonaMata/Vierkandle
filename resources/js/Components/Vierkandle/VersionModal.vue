<script setup lang="ts">
import DialogModal from "@/Components/DialogModal.vue";
import {onMounted, ref} from "vue";
import {useVersion} from "@/Composables/useVersion";
import markdownit from "markdown-it";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const showModal = ref(false);
const version = useVersion();
const notesToShow = ref([]);

const md = markdownit();

const releaseNotes = [
    {version: "0.0.0", content : () => import("@/ReleaseNotes/0.0.0.md?raw")},
    {version: "0.1.0", content: () => import("@/ReleaseNotes/0.1.0.md?raw")},
    {version: "0.1.1", content: () => import("@/ReleaseNotes/0.1.1.md?raw")},
    {version: "0.1.3", content: () => import("@/ReleaseNotes/0.1.3.md?raw")},
]

onMounted(async () => {
    if (version.value <= releaseNotes[releaseNotes.length - 1].version) {
        notesToShow.value = releaseNotes.filter((note) => note.version > version.value).reverse();
        if (notesToShow.value.length > 0) {
            for (const note of notesToShow.value) {
                const content = await note.content();
                console.log(md.render(content.default));
                note.content = md.render(content.default);
            }
            console.log(notesToShow.value);
            showModal.value = true;
        }
    }
});

const closeModal = () => {
    showModal.value = false;
    version.value = notesToShow.value[0].version;
}
</script>

<template>
    <DialogModal :show="showModal" @close="closeModal">
        <template #title>
            <div class="flex justify-between items-center">
                <ApplicationMark/>
                <span>Release Notes</span>
                <PrimaryButton @click="closeModal">Close</PrimaryButton>
            </div>
        </template>
        <template #content>
            <div class="min-w-full flex flex-col gap-10 prose dark:prose-invert prose-sm md:prose-base overflow-y-auto max-h-[70dvh]">
                <div v-for="(note, index) in notesToShow"
                     v-html="note.content"
                     :class="{ 'border-t border-gray-300 dark:border-gray-700 pt-10': index > 0}"
                >
                </div>
            </div>
        </template>
        <template #footer>
            <span class="prose dark:prose-invert">Suggesties zijn altijd welkom! Maak een <a href="https://github.com/JonaMata/Vierkandle/issues/new">issue</a> aan op GitHub.</span>
        </template>
    </DialogModal>
</template>

<style scoped>

</style>
