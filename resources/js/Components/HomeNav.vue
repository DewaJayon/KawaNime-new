<script setup>
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Input from "@/Components/ui/input/Input.vue";
import { Button } from "@/Components/ui/button";
import { ref, computed } from "vue";
import { onClickOutside } from "@vueuse/core";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

// State
const inSearchOpen = ref(false);
const searchContainer = ref(null);
const searchQuery = ref("");
const isDropdownOpen = ref(false);

// Dummy data anime
const animeList = ref([
    {
        id: 1,
        title: "Attack on Titan",
        category: "Action, Drama",
        image: "https://dummyimage.com/300x400/09f/fff.png",
    },
    {
        id: 2,
        title: "Demon Slayer",
        category: "Action, Fantasy",
        image: "https://dummyimage.com/300x400/09f/fff.png",
    },
    {
        id: 3,
        title: "Jujutsu Kaisen",
        category: "Action, Supernatural",
        image: "https://dummyimage.com/300x400/09f/fff.png",
    },
    {
        id: 4,
        title: "Spy x Family",
        category: "Comedy, Action",
        image: "https://dummyimage.com/300x400/09f/fff.png",
    },
]);

// Filter anime based on search query
const filteredAnime = computed(() => {
    if (!searchQuery.value) return [];
    return animeList.value.filter((anime) =>
        anime.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Methods
const toggleSearch = () => {
    inSearchOpen.value = !inSearchOpen.value;
    if (inSearchOpen.value) {
        setTimeout(() => {
            document.querySelector('input[type="text"]')?.focus();
        }, 100);
    }
};

const handleInput = () => {
    isDropdownOpen.value = searchQuery.value.length > 0;
};

// Auto close when clicking outside
onClickOutside(searchContainer, () => {
    inSearchOpen.value = false;
    isDropdownOpen.value = false;
});
</script>

<template>
    <header
        class="fixed top-0 w-full z-50 bg-gradient-to-b from-black to-transparent"
    >
        <nav class="flex items-center justify-between px-4 md:px-12 py-4">
            <div class="flex items-center space-x-8">
                <h1 class="text-2xl font-bold">
                    Kawa<span class="text-accent">Nime</span>
                </h1>
                <div class="hidden md:flex space-x-6">
                    <Link
                        href="/"
                        :class="{
                            'text-accent border-b-2 border-accent':
                                route().current('home'),
                        }"
                        class="capitalize hover:text-accent transition-all duration-500 ease-in-out"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('anime-list')"
                        :class="{
                            'text-accent border-b-2 border-accent':
                                route().current('anime-list'),
                        }"
                        class="capitalize hover:text-accent transition-all duration-500 ease-in-out"
                    >
                        Anime List
                    </Link>
                    <Link
                        href="/"
                        class="capitalize hover:text-accent transition-all duration-500 ease-in-out"
                    >
                        Genres
                    </Link>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative" ref="searchContainer">
                    <div class="flex items-center">
                        <Input
                            v-show="inSearchOpen"
                            v-model="searchQuery"
                            @input="handleInput"
                            type="text"
                            placeholder="Search anime..."
                            class="bg-gray-800 border border-gray-600 text-white px-4 py-2 rounded-md w-64 focus:outline-none focus:border-white transition-colors"
                        />
                        <Button
                            class="ml-2 border-none bg-transparent"
                            @click="toggleSearch"
                        >
                            <Icon
                                icon="material-symbols:search-rounded"
                                width="24"
                                height="24"
                            />
                        </Button>
                    </div>

                    <!-- Dropdown Results -->
                    <div
                        v-if="isDropdownOpen && filteredAnime.length > 0"
                        class="absolute top-full left-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto"
                    >
                        <div
                            v-for="anime in filteredAnime"
                            :key="anime.id"
                            class="flex items-center p-3 hover:bg-gray-800 cursor-pointer border-b border-gray-700 last:border-b-0"
                        >
                            <img
                                :src="anime.image"
                                :alt="anime.title"
                                class="w-12 h-16 object-cover rounded mr-3"
                            />
                            <div class="flex-1">
                                <h4 class="text-white font-medium text-sm">
                                    {{ anime.title }}
                                </h4>
                                <p class="text-gray-400 text-xs">
                                    {{ anime.category }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="isDropdownOpen && filteredAnime.length === 0"
                        class="absolute top-full left-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-md shadow-lg z-50 p-4 text-center"
                    >
                        <p class="text-gray-400 text-sm">
                            Tidak ada hasil untuk "{{ searchQuery }}"
                        </p>
                    </div>
                </div>

                <div class="w-8 h-8 rounded">
                    <DropdownMenu class="bg-black">
                        <DropdownMenuTrigger
                            as-child
                            class="outline-none cursor-pointer"
                        >
                            <Avatar>
                                <AvatarImage
                                    src="https://dummyimage.com/300/09f/fff.png"
                                />
                                <AvatarFallback>User</AvatarFallback>
                            </Avatar>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="bg-black text-white">
                            <DropdownMenuLabel>Akun Saya</DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <Link href="/profile">
                                <DropdownMenuItem
                                    class="cursor-pointer hover:text-black"
                                >
                                    Profile
                                </DropdownMenuItem>
                            </Link>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </nav>
    </header>
</template>
