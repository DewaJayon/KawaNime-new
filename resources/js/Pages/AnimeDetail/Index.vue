<script setup>
import { Button } from "@/Components/ui/button";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { computed } from "vue";
import { Separator } from "@/Components/ui/separator";
import { Card, CardContent } from "@/Components/ui/card";
import { AspectRatio } from "@/Components/ui/aspect-ratio";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import "dayjs/locale/id";

dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.locale("id");

const episodeCreatedAt = (episode) => {
    return dayjs.utc(episode.created_at).tz("Asia/Shanghai").fromNow();
};

const props = defineProps({
    anime: {
        type: Object,
        required: true,
    },
    episodes: {
        type: Object,
        required: true,
    },
});

const firstEpisodeSlug = computed(() => {
    return props.episodes[0]?.slug ?? null;
});
</script>

<template>
    <HomeLayout>
        <Head :title="`${props.anime.title}`" />
        <main class="max-w-7xl mx-auto px-4 py-6 pt-24">
            <div class="flex flex-col lg:flex-row gap-8 mb-12">
                <div class="relative">
                    <div class="relative w-[300px] h-[450px] mx-auto lg:mx-0">
                        <img
                            loading="lazy"
                            :src="`/${props.anime.thumbnail}`"
                            :alt="props.anime.title"
                            class="w-full h-full object-cover rounded-lg"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/30 rounded-lg"
                        />
                    </div>
                </div>

                <div class="flex-1 space-y-6">
                    <div>
                        <h2 class="text-4xl font-bold mb-2">
                            {{ props.anime.title }}
                        </h2>
                        <p class="text-muted-foreground mb-4 text-sm">
                            {{
                                props.anime.genres.map((g) => g.name).join(", ")
                            }}
                        </p>
                    </div>
                    <p class="text-gray-300 leading-relaxed max-w-2xl">
                        {{ props.anime.description }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <Link
                            v-if="firstEpisodeSlug"
                            :href="`/watch/${firstEpisodeSlug}`"
                        >
                            <Button
                                class="bg-accent text-black py-3 rounded font-semibold hover:bg-opacity-50 transition-colors flex items-center"
                            >
                                <Icon
                                    icon="gravity-ui:play-fill"
                                    width="16"
                                    height="16"
                                />
                                Tonton Episode 1
                            </Button>
                        </Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Status</span>
                            <p class="capitalize">
                                {{ props.anime.status }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-400">Total Episode</span>
                            <p class="capitalize">
                                {{ props.episodes.length }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-400"
                                >Durasi Per Episode</span
                            >
                            <p class="capitalize">
                                {{ props.episodes[0]?.duration ?? "-" }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-400">Studio</span>
                            <p class="capitalize">
                                {{ props.anime.studio }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8" v-if="props.episodes.length > 0">
                <div class="flex space-x-8">
                    <Button class="bg-transparent border-b-2 border-accent">
                        Episode
                    </Button>
                </div>
                <Separator class="bg-zinc-800" />

                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4"
                >
                    <Link
                        v-for="episode in props.episodes"
                        :key="episode.id"
                        :href="`/watch/${episode.slug}`"
                    >
                        <Card
                            class="group cursor-pointer bg-transparent border-none"
                        >
                            <CardContent
                                class="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105 p-0"
                            >
                                <AspectRatio :ratio="16 / 9">
                                    <img
                                        loading="lazy"
                                        :src="`/storage/${episode.thumbnail_url}`"
                                        :alt="episode.title"
                                        class="object-cover rounded-lg"
                                    />
                                </AspectRatio>
                            </CardContent>
                            <div class="mt-3">
                                <h3
                                    class="text-sm font-medium text-white line-clamp-2 group-hover:text-accent transition-colors capitalize"
                                >
                                    {{ episode.title }}
                                </h3>
                                <p
                                    class="text-xs text-gray-400 mt-1 capitalize"
                                >
                                    {{ episodeCreatedAt(episode) }}
                                </p>
                            </div>
                        </Card>
                    </Link>
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
