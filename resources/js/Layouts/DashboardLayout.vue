<script setup>
import { SidebarProvider } from "@/Components/ui/sidebar";
import DashboardSidebar from "@/Components/DashboardSidebar.vue";
import DashboardHeader from "@/Components/DashboardHeader.vue";
</script>

<template>
    <div class="flex h-screen bg-gray-900">
        <SidebarProvider>
            <DashboardSidebar />
            <div class="flex-1 flex flex-col overflow-hidden">
                <DashboardHeader />
                <main
                    class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-6"
                >
                    <slot />
                </main>
            </div>
        </SidebarProvider>
    </div>
</template>
