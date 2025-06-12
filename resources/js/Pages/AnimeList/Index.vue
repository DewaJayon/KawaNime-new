<script setup>
import { Button } from "@/Components/ui/button";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Card, CardContent } from "@/Components/ui/card";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
</script>

<template>
    <Head title="Anime List" />
    <HomeLayout>
        <main class="max-w-7xl mx-auto px-4 pt-24 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">List Anime</h1>
                <div class="flex items-center space-x-4">
                    <Select>
                        <SelectTrigger class="w-[180px] border-accent">
                            <SelectValue placeholder="Filter" />
                        </SelectTrigger>
                        <SelectContent class="bg-gray-400">
                            <SelectGroup>
                                <SelectItem value="apple"> Ongoing </SelectItem>
                                <SelectItem value="banana"> Movie </SelectItem>
                                <SelectItem value="blueberry">
                                    Series
                                </SelectItem>
                                <SelectItem value="blueberry">
                                    Live Action
                                </SelectItem>
                                <SelectItem value="grapes">
                                    Completed
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-6 text-gray-200">
                    Ongoing
                </h2>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
                >
                    <Card
                        v-for="i in 12"
                        :key="i"
                        class="group cursor-pointer bg-transparent border-none"
                    >
                        <CardContent
                            class="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105 p-0"
                        >
                            <img
                                src="https://dummyimage.com/300x420/fff.jpg"
                                alt=""
                                class="w-full h-64 object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center"
                            >
                                <Button
                                    class="opacity-0 group-hover:opacity-100 bg-accent text-black px-4 py-2 rounded-md font-medium transition-opacity"
                                    >Tonton</Button
                                >
                            </div>
                            <div class="absolute top-2 right-2">
                                <span>Ongoing</span>
                            </div>
                        </CardContent>
                        <div class="mt-3">
                            <h3
                                class="text-sm font-medium text-white line-clamp-2 group-hover:text-accent transition-colors"
                            >
                                asd
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">asd</p>
                        </div>
                    </Card>
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
