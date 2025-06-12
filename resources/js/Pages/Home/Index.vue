<script setup>
import { Head } from "@inertiajs/vue3";
import Carousel from "./Partials/Carousel.vue";
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import { Card, CardContent } from "@/Components/ui/card";
import HomeLayout from "@/Layouts/HomeLayout.vue";

const scrollRef = ref(null);

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
        <!-- Slider -->
        <Carousel />

        <div class="mb-8 relative">
            <h2 class="text-white text-xl font-semibold mb-4 px-4 md:px-12">
                Update Terbaru
            </h2>

            <!-- Tombol Kiri -->
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

            <!-- Tombol Kanan -->
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
                <Card
                    v-for="i in 10"
                    :key="i"
                    class="flex-none w-40 md:w-48 bg-black rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 cursor-pointer border-accent"
                >
                    <CardContent class="p-0">
                        <img
                            :src="`https://dummyimage.com/300x420/fff.jpg&text=Cover+${i}`"
                            alt=""
                            class="w-full h-60 object-cover"
                        />
                        <div class="p-2">
                            <h3
                                class="text-sm font-semibold text-white truncate"
                            >
                                Judul Anime {{ i }}
                            </h3>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </HomeLayout>
</template>
