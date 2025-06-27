<script setup>
import { Button } from "@/Components/ui/button";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Hls from "hls.js";
import Plyr from "plyr";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import "dayjs/locale/id";
import { onMounted } from "vue";

const props = defineProps({
    episode: {
        type: Object,
        required: true,
    },
    nextEpisode: {
        type: Object,
        required: false,
    },
    recomendations: {
        type: Object,
        required: false,
    },
});

dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.locale("id");

let recomendationCreated;
if (props.nextEpisode) {
    recomendationCreated = dayjs
        .utc(props.recomendations.created_at)
        .tz("Asia/Shanghai")
        .fromNow();
}

let nextEpisodeCreated;
if (props.nextEpisode) {
    nextEpisodeCreated = dayjs
        .utc(props.nextEpisode.created_at)
        .tz("Asia/Shanghai")
        .fromNow();
}

const dateCreated = dayjs
    .utc(props.episode.created_at)
    .tz("Asia/Shanghai")
    .fromNow();

onMounted(() => {
    const video = document.getElementById("player");
    const source = `/storage/${props.episode.video_url}`;

    const defaultOptions = {
        controls: [
            "play-large",
            "restart",
            "rewind",
            "play",
            "fast-forward",
            "progress",
            "current-time",
            "duration",
            "mute",
            "volume",
            "captions",
            "settings",
            "pip",
            "airplay",
            "fullscreen",
        ],
        settings: ["quality", "speed"],
    };

    if (Hls.isSupported()) {
        const hls = new Hls();
        hls.loadSource(source);

        hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
            const availableQualities = hls.levels.map((l) => l.height);

            defaultOptions.quality = {
                default: availableQualities[0],
                options: availableQualities,
                forced: true,
                onChange: (e) => updateQuality(e),
            };

            new Plyr(video, defaultOptions);
        });

        hls.attachMedia(video);
        window.hls = hls;
    } else {
        video.src = source;
        new Plyr(video, defaultOptions);
    }

    function updateQuality(newQuality) {
        window.hls.levels.forEach((level, levelIndex) => {
            if (level.height === newQuality) {
                window.hls.currentLevel = levelIndex;
            }
        });
    }
});
</script>

<template>
    <Head :title="`${episode.title}`" />
    <HomeLayout>
        <main class="max-w-7xl mx-auto px-4 py-6 pt-24">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div
                        class="relative bg-black rounded-lg overflow-hidden aspect-video cursor-pointer"
                    >
                        <video
                            id="player"
                            controls
                            crossorigin
                            playsinline
                            class="w-full h-full object-contain"
                        ></video>
                    </div>

                    <div class="mt-4">
                        <h1 class="text-xl font-bold mb-2">
                            {{ episode.title }}
                        </h1>
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400 text-sm capitalize">
                                {{ dateCreated }}
                            </div>
                            <div class="flex items-center space-x-4">
                                <Button
                                    variant="outline"
                                    class="bg-transparent border-accent hover:bg-gray-600/50 transition-all duration-500 ease-in-out text-accent"
                                    >Download</Button
                                >
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between p-4 bg-gray-900 rounded-lg"
                        >
                            <div class="flex items-center space-x-4">
                                <img
                                    :src="`/${episode.anime.thumbnail}`"
                                    alt=""
                                    class="h-[219px] w-[145px] rounded-lg"
                                />
                                <div
                                    class="block top-auto right-auto bottom-auto left-auto"
                                >
                                    <h1 class="text-lg font-semibold">
                                        {{ episode.anime.title }}
                                    </h1>
                                    <p class="text-gray-400 text-sm">
                                        {{ episode.anime.description }}
                                    </p>
                                    <div class="text-gray-400 text-sm mt-5">
                                        <Button
                                            v-for="genre in episode.anime
                                                .genres"
                                            :key="genre.id"
                                            class="bg-accent mr-2 hover:bg-accent/60 text-black font-medium capitalize transition-all duration-200 ease-in-out"
                                        >
                                            {{ genre.name }}
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Rekomendasi</h3>
                    <div class="space-y-4">
                        <Link
                            :href="route('watch', nextEpisode.slug)"
                            v-if="nextEpisode"
                        >
                            <div
                                class="flex space-x-3 cursor-pointer hover:bg-gray-900 p-2 rounded-lg transition-colors"
                            >
                                <div class="relative flex-shrink-0">
                                    <img
                                        loading="lazy"
                                        :src="`/storage/${nextEpisode.thumbnail_url}`"
                                        :alt="nextEpisode.title"
                                        class="w-40 h-24 object-cover rounded-lg"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="font-medium text-sm line-clamp-2 mb-1"
                                    >
                                        {{ nextEpisode.title }}
                                    </h4>
                                    <p class="text-gray-400 text-xs capitalize">
                                        {{
                                            episode.anime.genres
                                                .map((g) => g.name)
                                                .join(", ")
                                        }}
                                    </p>
                                    <p
                                        class="text-gray-400 text-xs capitalize mt-1"
                                    >
                                        {{ nextEpisodeCreated }}
                                    </p>
                                </div>
                            </div>
                        </Link>

                        <div
                            v-for="recomendation in recomendations"
                            :key="recomendation.id"
                            class="flex space-x-3 cursor-pointer hover:bg-gray-900 p-2 rounded-lg transition-colors"
                        >
                            <div class="relative flex-shrink-0">
                                <img
                                    loading="lazy"
                                    :src="`/storage/${recomendation.thumbnail_url}`"
                                    :alt="recomendation.title"
                                    class="w-40 h-24 object-cover rounded-lg"
                                />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="font-medium text-sm line-clamp-2 mb-1"
                                >
                                    {{ recomendation.title }}
                                </h4>
                                <p class="text-gray-400 text-xs">
                                    {{ recomendationCreated }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
