<script setup>
import HomeNav from "@/Components/HomeNav.vue";
import HomeFooter from "@/Components/HomeFooter.vue";
</script>

<template>
    <div class="bg-black min-h-screen text-white">
        <HomeNav />

        <slot />

        <HomeFooter />
    </div>
</template>
