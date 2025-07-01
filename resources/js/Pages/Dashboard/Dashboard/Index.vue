<script setup>
import { Head } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import DatabaseBackup from "./DatabaseBackup.vue";

const props = defineProps({
    sumAnime: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <Head title="Dashboard" />
    <DashboardLayout>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <Icon
                            icon="material-symbols:movie"
                            width="24"
                            height="24"
                            class="text-white"
                        />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">
                            Total Anime
                        </p>
                        <p class="text-2xl font-semibold text-white">
                            {{ props.sumAnime }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <Icon
                            icon="material-symbols:movie"
                            width="24"
                            height="24"
                            class="text-white"
                        />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">
                            Total Anime
                        </p>
                        <p class="text-2xl font-semibold text-white">100</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <Icon
                            icon="material-symbols:movie"
                            width="24"
                            height="24"
                            class="text-white"
                        />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">
                            Total Anime
                        </p>
                        <p class="text-2xl font-semibold text-white">100</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <Icon
                            icon="material-symbols:movie"
                            width="24"
                            height="24"
                            class="text-white"
                        />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">
                            Total Anime
                        </p>
                        <p class="text-2xl font-semibold text-white">100</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <DatabaseBackup />

            <div class="bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-200">Chart</h2>
                Chart
            </div>
        </div>
    </DashboardLayout>
</template>
