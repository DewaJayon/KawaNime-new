<script setup>
import { Link } from "@inertiajs/vue3";

const getYear = () => {
    return new Date().getFullYear();
};
</script>

<template>
    <footer class="bg-black py-12 px-4 md:px-12 mt-16">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-gray-400">
                <div>
                    <h3 class="text-white font-semibold mb-4">Link Utama</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                :href="route('home')"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                            >
                                Home
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('anime-list')"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                            >
                                Anime List
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('profile.edit')"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                            >
                                Profile
                            </Link>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Lainnya</h3>
                    <ul class="space-y-2">
                        <li>
                            <a
                                target="_blank"
                                href="https://sociabuzz.com/dewa_jayon/donate"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                            >
                                Support via sociabuzz
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
            >
                <p>© {{ getYear() }} KawaNime. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>
