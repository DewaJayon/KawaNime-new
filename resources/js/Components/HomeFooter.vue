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
                    <h3 class="text-white font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/"
                                class="text-white hover:text-accent transition-all duration-500 ease-in-out"
                                >asdasd</Link
                            >
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
