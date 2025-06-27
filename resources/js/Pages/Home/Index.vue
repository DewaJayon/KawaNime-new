<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Carousel from "./Partials/Carousel.vue";
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import { Card, CardContent } from "@/Components/ui/card";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import "dayjs/locale/id";

dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.locale("id");

const scrollRef = ref(null);

const props = defineProps({
    episodes: {
        type: Array,
        required: true,
    },
});

const episodeCreatedAt = (episode) => {
    return dayjs.utc(episode.created_at).tz("Asia/Shanghai").fromNow();
};

function scrollLeft() {
    scrollRef.value.scrollBy({ left: -300, behavior: "smooth" });
}

function scrollRight() {
    scrollRef.value.scrollBy({ left: 300, behavior: "smooth" });
}
</script>

<template>
    <Head title="Home" />
    <HomeLayout>
        <Carousel />

        <div class="mb-8 relative">
            <h2 class="text-white text-xl font-semibold mb-4 px-4 md:px-12">
                Update Episode Terbaru
            </h2>

            <Button
                @click="scrollLeft"
                class="hidden md:flex absolute left-4 top-1/2 transform -translate-y-1/2 z-20 bg-zinc-600 hover:bg-opacity-75 p-2 rounded-full"
            >
                <Icon
                    icon="iconamoon:arrow-left-2-bold"
                    width="30"
                    height="30"
                    class="text-accent"
                />
            </Button>

            <Button
                @click="scrollRight"
                class="hidden md:flex absolute right-4 top-1/2 transform -translate-y-1/2 z-20 bg-zinc-600 hover:bg-opacity-75 p-2 rounded-full"
            >
                <Icon
                    icon="iconamoon:arrow-right-2-bold"
                    width="30"
                    height="30"
                    class="text-accent"
                />
            </Button>

            <div
                ref="scrollRef"
                class="flex overflow-x-auto space-x-4 px-4 md:px-12 pb-4 scrollbar-hide scroll-smooth"
            >
                <Link
                    v-for="episode in episodes"
                    :key="episode.id"
                    :href="route('watch', episode.slug)"
                >
                    <Card
                        class="group cursor-pointer bg-transparent border-none"
                    >
                        <CardContent
                            class="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105 p-0"
                        >
                            <img
                                :src="`/${episode.anime.thumbnail}`"
                                :alt="episode.title"
                                class="w-full h-64 object-cover"
                            />
                        </CardContent>
                        <div class="mt-3">
                            <h3
                                class="text-sm font-medium text-white line-clamp-2 group-hover:text-accent transition-colors capitalize"
                            >
                                {{ episode.title }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1 capitalize">
                                {{ episodeCreatedAt(episode) }}
                            </p>
                        </div>
                    </Card>
                </Link>
            </div>
        </div>
    </HomeLayout>
</template>
