<script setup>
import { SidebarProvider } from "@/Components/ui/sidebar";
import DashboardSidebar from "@/Components/DashboardSidebar.vue";
import DashboardHeader from "@/Components/DashboardHeader.vue";
import { Toaster } from "@/Components/ui/sonner";
import "vue-sonner/style.css";
</script>

<template>
    <div class="flex h-screen bg-gray-900">
        <SidebarProvider>
            <DashboardSidebar />
            <div class="flex-1 flex flex-col overflow-hidden">
                <DashboardHeader />
                <main
                    class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-6 scrollbar-hide"
                >
                    <slot />
                </main>
            </div>
        </SidebarProvider>
        <Toaster position="top-right" richColors />
    </div>
</template>
