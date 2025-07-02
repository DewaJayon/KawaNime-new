<script setup>
import { Head } from "@inertiajs/vue3";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import ProfilePicture from "./Partials/ProfilePicture.vue";
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <HomeLayout>
        <main class="max-w-7xl mx-auto px-4 pt-24 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Profile</h1>
            </div>
            <div
                class="rounded-2xl border border-slate-600/50 p-5 bg-white/[0.03] lg:p-6"
            >
                <div class="p-5 mb-6 border rounded-2xl border-gray-800 lg:p-6">
                    <div
                        class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between"
                    >
                        <div
                            class="flex flex-col items-center w-full gap-6 xl:flex-row"
                        >
                            <ProfilePicture />
                            <div class="order-3 xl:order-2">
                                <h4
                                    class="mb-2 text-lg font-semibold text-center text-white xl:text-left"
                                >
                                    {{ $page.props.auth.user.name }}
                                </h4>
                            </div>
                        </div>

                        <Button
                            variant="outline"
                            class="bg-transparent rounded-xl text-accent border-accent hover:bg-gray-600/50 transition-all duration-500 ease-in-out"
                        >
                            <Icon
                                icon="proicons:pencil"
                                width="24"
                                height="24"
                            />
                            Edit
                        </Button>
                        <DeleteUserForm />
                    </div>
                </div>

                <div class="p-5 mb-6 border rounded-2xl border-gray-800 lg:p-6">
                    <UpdateProfileInformationForm />
                </div>

                <div
                    class="p-5 mb-6 border rounded-2xl border-gray-800 lg:p-6"
                    v-if="$page.props.auth.user.provider === 'web'"
                >
                    <UpdatePasswordForm />
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
