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
    filteredAnimes: {
        type: Object,
        required: true,
    },
});

const filter = ref("update-terbaru");

const animeFiltered = ref(props.filteredAnimes);

const isLoading = ref(false);

watch(filter, (selectedFilter) => {
    isLoading.value = true;
    router.get(
        route("anime-list"),
        {
            filter: selectedFilter,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            showProgress: true,
            onSuccess: () => {
                animeFiltered.value = props.filteredAnimes;
                isLoading.value = false;
            },
        }
    );
});
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
                    <template v-for="anime in animeFiltered">
                        <Link
                            v-if="filter === 'update-terbaru'"
                            v-for="episode in anime.episodes"
                            :href="`/watch/${episode.slug}`"
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

                        <Link
                            v-else
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
                    v-if="filterList.length > 12"
                    class="flex items-center justify-center w-full mx-auto pt-8"
                >
                    <Button
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
