<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";

const props = defineProps({
    episode: {
        type: Object,
        required: true,
    },
    anime: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`${props.episode.title} - Episodes`" />
    <DashboardLayout>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h1 class="text-xl font-semibold mb-4 text-white">
                {{ props.episode.title }}
            </h1>
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
                    <li>
                        <Link
                            class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-500 transition-all ease-in-out duration-300"
                            :href="route('dashboard.anime.index')"
                        >
                            Anime
                            <Icon
                                icon="material-symbols:chevron-right-rounded"
                                width="22"
                                height="22"
                            />
                        </Link>
                    </li>
                    <li class="text-sm text-white/90">Episode</li>
                </ol>
            </nav>
        </div>
        <div class="rounded-2xl borderp-5 border-gray-700 bg-white/[0.03]">
            <div
                class="flex flex-wrap items-center justify-between gap-3 px-5 pt-5"
            >
                <h3 class="text-lg font-semibold text-white">
                    {{ props.episode.title }}
                </h3>
                <Link
                    :href="route('dashboard.episode.index', props.anime.slug)"
                    class="bg-transparent hover:bg-accent text-white hover:text-black transition-all flex items-center rounded-md gap-2 px-1 py-2 border border-white duration-300 ease-in-out"
                >
                    <Icon icon="tabler:arrow-left" width="20" height="20" />
                    Kembali ke daftar episode
                </Link>
            </div>
            <Separator class="mt-4 bg-gray-700/90" />
            <div class="sm:p-6">form aja ntar</div>
        </div>
    </DashboardLayout>
</template>
