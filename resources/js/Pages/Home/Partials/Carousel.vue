<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Icon } from "@iconify/vue";
import { Button } from "@/Components/ui/button";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const heroMovies = ref(page.props.banners);

const currentSlide = ref(0);
let intervalId = null;

const startSlider = () => {
    intervalId = setInterval(() => {
        nextSlide();
    }, 5000);
};

const stopSlider = () => {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
};

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % heroMovies.value.length;
};

const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + heroMovies.value.length) %
        heroMovies.value.length;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

onMounted(() => {
    startSlider();
});

onUnmounted(() => {
    stopSlider();
});
</script>

<template>
    <div class="relative h-screen overflow-hidden">
        <div
            class="absolute inset-0 transition-all duration-1000 ease-in-out"
            :style="{
                backgroundImage: `url(${heroMovies[currentSlide].image})`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
            }"
        >
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-transparent"
            ></div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"
            ></div>
        </div>

        <div class="relative z-10 flex items-center h-full px-4 md:px-12">
            <div class="max-w-lg">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    {{ heroMovies[currentSlide].headline }}
                </h1>
                <p class="text-lg md:text-xl mb-8 text-gray-200 line-clamp-3">
                    {{ heroMovies[currentSlide].subheadline }}
                </p>
                <div class="flex space-x-4">
                    <Link
                        v-if="heroMovies[currentSlide].anime.episodes[0]"
                        :href="
                            route(
                                'watch',
                                heroMovies[currentSlide].anime.episodes[0].slug
                            )
                        "
                    >
                        <Button
                            class="bg-accent text-black px-8 py-3 rounded font-semibold hover:bg-opacity-50 transition-colors flex items-center"
                        >
                            <Icon
                                icon="gravity-ui:play-fill"
                                width="16"
                                height="16"
                            />
                            Play EP 1
                        </Button>
                    </Link>
                    <Button
                        class="bg-gray-600 text-white px-8 py-3 rounded font-semibold hover:bg-opacity-50 transition-colors flex items-center"
                    >
                        <Icon
                            icon="clarity:info-solid"
                            width="30"
                            height="30"
                        />
                        More Info
                    </Button>
                </div>
            </div>
        </div>

        <Button
            @click="prevSlide"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 transition-colors"
            @mouseenter="stopSlider"
            @mouseleave="startSlider"
        >
            <Icon
                icon="iconamoon:arrow-left-2-bold"
                width="30"
                height="30"
                class="text-accent"
            />
        </Button>
        <Button
            @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 transition-colors"
            @mouseenter="stopSlider"
            @mouseleave="startSlider"
        >
            <Icon
                icon="iconamoon:arrow-right-2-bold"
                width="30"
                height="30"
                class="text-accent"
            />
        </Button>

        <div
            class="absolute bottom-20 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20"
        >
            <button
                v-for="(_, index) in heroMovies"
                :key="index"
                @click="goToSlide(index)"
                @mouseenter="stopSlider"
                @mouseleave="startSlider"
                class="w-3 h-3 rounded-full transition-colors"
                :class="index === currentSlide ? 'bg-white' : 'bg-gray-500'"
            />
        </div>
    </div>
</template>
