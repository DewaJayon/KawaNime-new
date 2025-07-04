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
        <meta name="description" :content="description" />
    </Head>
    <div class="bg-black min-h-screen text-white">
        <HomeNav />

        <slot />

        <HomeFooter />

        <Toaster position="top-right" richColors />
    </div>
</template>
