<script setup>
import { Button } from "@/Components/ui/button";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { Card, CardContent } from "@/Components/ui/card";
import Spinner from "@/Components/Spinner.vue";
import { filterList } from "./partials/filter-list";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { ref, watch } from "vue";
import { Badge } from "@/Components/ui/badge";

const props = defineProps({
    animes: Object,
    episodes: Object, // Tambahkan ini
    filter: String,
});

const filter = ref(props.filter ?? "update-terbaru");
const isUpdateTerbaru = filter.value === "update-terbaru";

const animeList = ref(
    isUpdateTerbaru
        ? [...(props.episodes?.data ?? [])]
        : [...(props.animes?.data ?? [])]
);
const nextPageUrl = ref(
    isUpdateTerbaru
        ? props.episodes?.next_page_url
        : props.animes?.next_page_url
);

const isLoading = ref(false);

watch(filter, (selectedFilter) => {
    isLoading.value = true;

    router.get(
        route("anime-list"),
        { filter: selectedFilter },
        {
            preserveScroll: true,
            preserveState: false,
            only: ["animes", "episodes", "filter"],
            onSuccess: (page) => {
                const isUpdateTerbaru = selectedFilter === "update-terbaru";

                animeList.value = isUpdateTerbaru
                    ? page.props.episodes?.data ?? []
                    : page.props.animes?.data ?? [];

                nextPageUrl.value = isUpdateTerbaru
                    ? page.props.episodes?.next_page_url
                    : page.props.animes?.next_page_url;

                isLoading.value = false;
            },
        }
    );
});

const loadMore = () => {
    if (!nextPageUrl.value) return;

    isLoading.value = true;

    router.get(
        nextPageUrl.value,
        { filter: filter.value },
        {
            preserveScroll: true,
            preserveState: true,
            only: ["animes", "episodes"],
            onSuccess: (page) => {
                const isUpdateTerbaru = filter.value === "update-terbaru";

                const newData = isUpdateTerbaru
                    ? page.props.episodes?.data ?? []
                    : page.props.animes?.data ?? [];

                animeList.value.push(...newData);

                nextPageUrl.value = isUpdateTerbaru
                    ? page.props.episodes?.next_page_url
                    : page.props.animes?.next_page_url;

                isLoading.value = false;
            },
        }
    );
};
</script>

<template>
    <Head title="Anime List" />
    <HomeLayout>
        <main class="max-w-7xl mx-auto px-4 pt-24 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">List Anime</h1>
                <div class="flex items-center space-x-4">
                    <Select v-model="filter">
                        <SelectTrigger class="w-[180px] border-accent">
                            <SelectValue placeholder="Filter" />
                        </SelectTrigger>
                        <SelectContent class="bg-black text-white">
                            <SelectGroup>
                                <SelectLabel>Filter</SelectLabel>
                                <SelectItem
                                    class="cursor-pointer hover:text-black focus:text-black"
                                    v-for="filter in filterList"
                                    :key="filter.value"
                                    :value="filter.value"
                                >
                                    {{ filter.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-6 text-gray-200">
                    {{ filterList.find((f) => f.value === filter)?.label }}
                </h2>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
                >
                    <template v-if="filter === 'update-terbaru'">
                        <Link
                            v-for="episode in animeList"
                            :key="episode.id"
                            :href="`/watch/${episode.slug}`"
                        >
                            <Card
                                class="group cursor-pointer bg-transparent border-none"
                            >
                                <CardContent
                                    class="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105 p-0"
                                >
                                    <img
                                        :src="`/${episode.anime?.thumbnail}`"
                                        :alt="episode.anime?.title"
                                    />
                                    <div class="absolute top-2 right-2">
                                        <Badge
                                            variant="outline"
                                            class="capitalize text-accent border-accent bg-black/50"
                                        >
                                            Episode {{ episode.episode_number }}
                                        </Badge>
                                    </div>
                                </CardContent>
                                <div class="mt-3">
                                    <h3
                                        class="text-sm font-medium text-white line-clamp-2 group-hover:text-accent transition-colors"
                                    >
                                        {{ episode.title }}
                                    </h3>
                                </div>
                            </Card>
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            v-for="anime in animeList"
                            :key="anime.id"
                            :href="`${route('anime-detail', anime.slug)}`"
                        >
                            <Card
                                class="group cursor-pointer bg-transparent border-none"
                            >
                                <CardContent
                                    class="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105 p-0"
                                >
                                    <img
                                        :src="`/${anime.thumbnail}`"
                                        :alt="anime.title"
                                        class="w-full h-64 object-cover"
                                    />
                                </CardContent>
                                <div class="mt-3">
                                    <h3
                                        class="text-sm font-medium text-white line-clamp-2 group-hover:text-accent transition-colors capitalize"
                                    >
                                        {{ anime.title }}
                                    </h3>
                                </div>
                            </Card>
                        </Link>
                    </template>
                </div>

                <div
                    class="flex items-center justify-center w-full mx-auto pt-8"
                >
                    <Button
                        v-if="nextPageUrl"
                        @click="loadMore"
                        variant="outline"
                        class="bg-transparent border-accent hover:bg-gray-600/50 transition-all duration-500 ease-in-out text-accent"
                    >
                        Load More...
                    </Button>
                </div>

                <Spinner
                    v-if="isLoading"
                    class="flex items-center justify-center mx-auto mt-4 text-accent w-8 h-8"
                />
            </div>
        </main>
    </HomeLayout>
</template>
