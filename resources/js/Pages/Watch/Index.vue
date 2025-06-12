<script setup>
import { Button } from "@/Components/ui/button";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Watch" />
    <HomeLayout>
        <main class="max-w-7xl mx-auto px-4 py-6 pt-24">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div
                        class="relative bg-black rounded-lg overflow-hidden aspect-video cursor-pointer"
                    >
                        <vue-plyr>
                            <video controls crossorigin playsinline>
                                <source
                                    src="/storage/anime/dummy/dummy-1080.mp4"
                                    type="video/mp4"
                                    size="1080"
                                />
                                <source
                                    src="/storage/anime/dummy/dummy-720.mp4"
                                    type="video/mp4"
                                    size="720"
                                />
                                <source
                                    src="/storage/anime/dummy/dummy-480.mp4"
                                    type="video/mp4"
                                    size="480"
                                />
                            </video>
                        </vue-plyr>
                    </div>

                    <div class="mt-4">
                        <h1 class="text-xl font-bold mb-2">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Impedit tempora aut accusantium?
                        </h1>
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400 text-sm">
                                1 hari yang lalu
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
                                    src="https://dummyimage.com/300x420/fff.jpg"
                                    alt=""
                                    class="h-[219px] w-[145px] rounded-lg"
                                />
                                <div
                                    class="block static top-auto right-auto bottom-auto left-auto h-[214px] w-[613px]"
                                >
                                    <h1 class="text-lg font-semibold">Dummy</h1>
                                    <p class="text-gray-400 text-sm">
                                        Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna
                                        aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris
                                        nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit
                                        in voluptate velit esse cillum dolore eu
                                        fugiat nulla pariatur. Excepteur sint
                                        occaecat cupidatat non proident, sunt in
                                        culpa qui officia deserunt mollit anim
                                        id est laborum.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Rekomendasi</h3>
                    <div class="space-y-4">
                        <div
                            v-for="i in 10"
                            :key="i"
                            class="flex space-x-3 cursor-pointer hover:bg-gray-900 p-2 rounded-lg transition-colors"
                        >
                            <div class="relative flex-shrink-0">
                                <img
                                    src="https://dummyimage.com/16:9x1080"
                                    alt=""
                                    class="w-40 h-24 object-cover rounded-lg"
                                />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="font-medium text-sm line-clamp-2 mb-1"
                                >
                                    Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit.
                                </h4>
                                <p class="text-gray-400 text-xs">
                                    1 Tahun Lalu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
