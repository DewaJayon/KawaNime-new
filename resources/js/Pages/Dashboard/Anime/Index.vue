<script setup>
import { DataTable } from "@/Components/ui/data-table";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { AnimeColumn } from "./partials/anime-column";
import AnimeCreateForm from "./form/AnimeCreateForm.vue";
import { Separator } from "@/Components/ui/separator";
import { Icon } from "@iconify/vue";

const props = defineProps({
    animes: {
        type: Object,
        required: true,
    },
    genres: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head title="Anime" />
    <DashboardLayout>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h1 class="text-xl font-semibold mb-4 text-white">Daftar Anime</h1>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <Link
                            class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-500 transition-all ease-in-out duration-300"
                            :href="route('dashboard')"
                        >
                            Home
                            <Icon
                                icon="material-symbols:chevron-right-rounded"
                                width="22"
                                height="22"
                            />
                        </Link>
                    </li>
                    <li class="text-sm text-white/90">Anime</li>
                </ol>
            </nav>
        </div>
        <div class="rounded-2xl borderp-5 border-gray-700 bg-white/[0.03]">
            <div
                class="flex flex-wrap items-center justify-between gap-3 px-5 pt-5"
            >
                <h3 class="text-lg font-semibold text-white">Anime</h3>
                <AnimeCreateForm :genres="genres" />
            </div>
            <Separator class="mt-4 bg-gray-700/90" />
            <div class="sm:p-6">
                <DataTable
                    :data="animes.data"
                    :columns="AnimeColumn"
                    :route-name="'dashboard.anime.index'"
                    :pagination="animes"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
