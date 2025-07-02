<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Input from "@/Components/ui/input/Input.vue";
import { Button } from "@/Components/ui/button";
import { ref, computed, watch } from "vue";
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
import Spinner from "./Spinner.vue";
import axios from "axios";

const inSearchOpen = ref(false);
const searchContainer = ref(null);
const searchQuery = ref("");
const isDropdownOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user ?? null);

const animeList = ref([]);

let timeout;
const isLoadingSearch = ref(false);

watch(searchQuery, (newSearchQuery) => {
    isLoadingSearch.value = true;
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        axios
            .get(route("anime.search"), {
                params: { q: newSearchQuery },
            })
            .then((response) => {
                animeList.value = response.data.animes;
            })
            .finally(() => {
                isLoadingSearch.value = false;
            });
    }, 300);
});

const filteredAnime = computed(() => {
    if (!searchQuery.value || !animeList.value) return [];
    return animeList.value;
});

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

onClickOutside(searchContainer, () => {
    inSearchOpen.value = false;
    isDropdownOpen.value = false;
});

function getColorFromName(name) {
    const colors = [
        "bg-red-500",
        "bg-orange-500",
        "bg-amber-500",
        "bg-yellow-500",
        "bg-lime-500",
        "bg-green-500",
        "bg-emerald-500",
        "bg-teal-500",
        "bg-cyan-500",
        "bg-sky-500",
        "bg-blue-500",
        "bg-indigo-500",
        "bg-violet-500",
        "bg-purple-500",
        "bg-pink-500",
        "bg-rose-500",
    ];

    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }

    const index = Math.abs(hash) % colors.length;
    return colors[index];
}

let avatarColor;
if (user.value) {
    avatarColor = getColorFromName(user.value.name);
}
</script>

<template>
    <header
        class="fixed top-0 w-full z-50 bg-gradient-to-b from-black to-transparent"
    >
        <nav class="flex items-center justify-between px-4 md:px-12 py-4">
            <div class="flex items-center space-x-8">
                <Link :href="route('home')">
                    <h1 class="text-2xl font-bold">
                        Kawa<span class="text-accent">Nime</span>
                    </h1>
                </Link>
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

                    <div
                        v-if="isDropdownOpen && filteredAnime.length > 0"
                        class="absolute top-full left-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto"
                    >
                        <Link
                            v-for="anime in filteredAnime"
                            :key="anime.id"
                            :href="route('anime-detail', anime.slug)"
                        >
                            <div
                                class="flex items-center p-3 hover:bg-gray-800 cursor-pointer border-b border-gray-700 last:border-b-0"
                            >
                                <img
                                    :src="`/${anime.thumbnail}`"
                                    :alt="anime.title"
                                    class="w-12 h-16 object-cover rounded mr-3"
                                />
                                <div class="flex-1">
                                    <h4 class="text-white font-medium text-sm">
                                        {{ anime.title }}
                                    </h4>
                                    <p class="text-gray-400 text-xs">
                                        {{
                                            anime.genres
                                                .map((g) => g.name)
                                                .join(", ")
                                        }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div
                        v-if="isDropdownOpen && filteredAnime.length === 0"
                        class="absolute top-full left-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-md shadow-lg z-50 p-4 text-center"
                    >
                        <Spinner
                            v-if="isLoadingSearch"
                            v-show="isLoadingSearch"
                            class="mx-auto"
                        />
                        <p v-else class="text-gray-400 text-sm">
                            Tidak ada hasil untuk "{{ searchQuery }}"
                        </p>
                    </div>
                </div>

                <div class="w-8 h-8 rounded">
                    <div v-if="user">
                        <DropdownMenu>
                            <DropdownMenuTrigger
                                as-child
                                class="outline-none cursor-pointer"
                            >
                                <Avatar
                                    class="h-8 w-8 flex items-center justify-center rounded-full overflow-hidden"
                                >
                                    <AvatarImage
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                    />
                                    <AvatarFallback
                                        :class="[
                                            avatarColor,
                                            'text-white font-bold w-full h-full flex items-center justify-center rounded-full',
                                        ]"
                                    >
                                        {{
                                            user.name
                                                .split(" ")
                                                .map((n) => n[0])
                                                .join("")
                                                .toUpperCase()
                                        }}
                                    </AvatarFallback>
                                </Avatar>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="bg-gray-800 text-white">
                                <DropdownMenuLabel>Akun Saya</DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <Link
                                    v-if="
                                        $page.props.auth.user.role === 'admin'
                                    "
                                    :href="route('dashboard')"
                                >
                                    <DropdownMenuItem
                                        class="cursor-pointer hover:text-black"
                                    >
                                        Dashboard Admin
                                    </DropdownMenuItem>
                                </Link>
                                <Link :href="route('profile.edit')">
                                    <DropdownMenuItem
                                        class="cursor-pointer hover:text-black"
                                    >
                                        Profile
                                    </DropdownMenuItem>
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    as="button"
                                    method="post"
                                    class="w-full hover:bg-accent hover:text-slate-900 cursor-pointer transition-all ease-in-out duration-300 rounded"
                                >
                                    <DropdownMenuItem class="cursor-pointer">
                                        Logout
                                    </DropdownMenuItem>
                                </Link>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                    <div v-else>
                        <Link
                            :href="route('login')"
                            class="w-8 h-8 flex items-center justify-center rounded hover:bg-accent hover:text-slate-900 transition-all duration-200 ease-in-out"
                        >
                            <Icon
                                icon="material-symbols:login-rounded"
                                width="24"
                                height="24"
                            />
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
