<script setup>
import HomeNav from "@/Components/HomeNav.vue";
import HomeFooter from "@/Components/HomeFooter.vue";
import { Toaster } from "@/Components/ui/sonner";
import "vue-sonner/style.css";
</script>

<template>
    <div class="bg-black min-h-screen text-white">
        <HomeNav />

        <slot />

        <HomeFooter />

        <Toaster position="top-right" richColors />
    </div>
</template>
