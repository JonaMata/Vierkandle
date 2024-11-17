<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextLink from "@/Components/TextLink.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    donottrack: props.user.donottrack,
});

const updatePrivacySettings = () => {

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <FormSection @submitted="updatePrivacySettings">
        <template #title>
            Privacy Settings
        </template>

        <template #description>
            Update your account's privacy settings. Also make sure to check out the <TextLink new-page :href="route('policy.show')">Privacy Policy</TextLink>.

        </template>

        <template #form>

            <!-- Name -->
<!--            <div class="col-span-6 sm:col-span-4">-->
<!--                <InputLabel for="name" value="Name" />-->
<!--                <TextInput-->
<!--                    id="name"-->
<!--                    v-model="form.name"-->
<!--                    type="text"-->
<!--                    class="mt-1 block w-full"-->
<!--                    required-->
<!--                    autocomplete="name"-->
<!--                />-->
<!--                <InputError :message="form.errors.name" class="mt-2" />-->
<!--            </div>-->

            <div class="col-span-6 sm:col-span-4 block mt-4">
                <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-gray-100">Tracking preferences</h3>
                <label class="flex items-center text-gray-600 dark:text-gray-400">
                    <Checkbox v-model:checked="form.donottrack" name="donottrack" />
                    <span class="ms-2 text-sm">Do not track me across devices.</span>
                </label>
            </div>



        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
