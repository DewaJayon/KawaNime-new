<script setup>
import HomeNav from "@/Components/HomeNav.vue";
import HomeFooter from "@/Components/HomeFooter.vue";
import { Toaster } from "@/Components/ui/sonner";
import "vue-sonner/style.css";
import { computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";

const page = usePage();

const description = computed(
    () =>
        page.props.description ||
        "Nonton anime sub indo HD gratis, update setiap hari hanya di KawaNime!"
);
</script>

<template>
    <Head>
        <link rel="canonical" href="https://kawanime.dewajayon.my.id/" />

        <title>Kawanime - Streaming Anime Sub Indo Terbaik</title>
        <meta name="description" :content="description" />

        <!-- Open Graph -->
        <meta
            property="og:title"
            content="Kawanime - Nonton Anime Sub Indo HD"
        />
        <meta property="og:description" :content="description" />
        <meta property="og:url" content="https://kawanime.dewajayon.my.id" />
        <meta property="og:type" content="website" />
        <meta
            property="og:image"
            content="https://kawanime.dewajayon.my.id/img/logo.jpg"
        />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta
            name="twitter:title"
            content="Kawanime - Nonton Anime Sub Indo HD"
        />
        <meta name="twitter:description" :content="description" />
        <meta
            name="twitter:image"
            content="https://kawanime.dewajayon.my.id/img/logo.jpg"
        />
    </Head>
    <div class="bg-black min-h-screen text-white">
        <HomeNav />

        <slot />

        <HomeFooter />

        <Toaster position="top-right" richColors />
    </div>
</template>
