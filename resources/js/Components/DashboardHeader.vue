<script setup>
import { SidebarTrigger } from "@/Components/ui/sidebar";
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
</script>

<template>
    <header class="bg-gray-800 shadow-sm border-b border-gray-700">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center">
                <SidebarTrigger
                    class="text-white hover:text-slate-700 transition-all duration-500 ease-in-out"
                />
                <h2 class="ml-4 text-xl font-semibold text-white">Dashboard</h2>
            </div>
            <div class="flex items-center space-x-4">
                <Button
                    class="bg-transparent rounded-full border border-gray-500/50 hover:bg-gray-600/50 transition-all duration-500 ease-in-out"
                >
                    <Icon
                        icon="mingcute:notification-line"
                        width="24"
                        height="24"
                        class="text-white"
                    />
                </Button>
                <div class="relative">
                    <Avatar class="h-8 w-8 flex items-center">
                        <AvatarImage
                            src="https://github.com/unovue.png"
                            alt="@unovue"
                        />
                        <AvatarFallback>CN</AvatarFallback>
                    </Avatar>
                </div>
            </div>
        </div>
    </header>
</template>
