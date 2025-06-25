<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Separator } from "@/Components/ui/separator";
import { Button } from "@/Components/ui/button";
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from "@/Components/ui/number-field";
import InputError from "@/Components/InputError.vue";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import Hls from "hls.js";
import Plyr from "plyr";
import { onMounted } from "vue";

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

const form = useForm({
    title: props.episode.title,
    episode_number: props.episode.episode_number,
    duration: props.episode.duration,
    description: props.episode.description,
    image: props.episode.image,
});

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
        settings: ["quality", "speed", "loop"], // Tambahkan ini
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
            <div class="sm:p-6">
                <form
                    id="form"
                    class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
                >
                    <div class="flex flex-col gap-4">
                        <div>
                            <Label class="text-white">Judul</Label>
                            <Input
                                type="text"
                                id="title"
                                placeholder="Contoh: Demon Slayer Kimetsu no Yaiba Episode 1"
                                v-model="form.title"
                                required
                                autocomplete="off"
                                class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div>
                            <NumberField
                                id="episode_number"
                                :default-value="form.episode_number"
                                :min="0"
                            >
                                <Label for="episode_number" class="text-white"
                                    >Nomor Episode</Label
                                >
                                <NumberFieldContent>
                                    <NumberFieldDecrement
                                        :disabled="
                                            form.episode_number === 1 ||
                                            form.episode_number === 0
                                        "
                                        @click="form.episode_number--"
                                        class="disabled:opacity-50 text-white"
                                    />
                                    <NumberFieldInput
                                        v-model="form.episode_number"
                                        @change="
                                            form.episode_number =
                                                $event.target.value
                                        "
                                        type="number"
                                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                                    />
                                    <NumberFieldIncrement
                                        @click="form.episode_number++"
                                        class="text-white"
                                    />
                                </NumberFieldContent>
                            </NumberField>
                            <InputError :message="form.errors.episode_number" />
                        </div>

                        <div>
                            <NumberField
                                id="duration"
                                :default-value="form.duration"
                                :min="0"
                            >
                                <Label for="duration" class="text-white"
                                    >Durasi Episode (Menit)</Label
                                >
                                <NumberFieldContent>
                                    <NumberFieldDecrement
                                        :disabled="
                                            form.duration === 1 ||
                                            form.duration === 0
                                        "
                                        @click="form.duration--"
                                        class="disabled:opacity-50 text-white"
                                    />
                                    <NumberFieldInput
                                        @change="
                                            form.duration = $event.target.value
                                        "
                                        v-model="form.duration"
                                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                                    />
                                    <NumberFieldIncrement
                                        @click="form.duration++"
                                        class="text-white"
                                    />
                                </NumberFieldContent>
                            </NumberField>
                            <InputError :message="form.errors.duration" />
                        </div>
                    </div>

                    <div>
                        <video
                            id="player"
                            controls
                            crossorigin
                            playsinline
                        ></video>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>
