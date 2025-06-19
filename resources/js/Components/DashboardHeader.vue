<script setup>
import { SidebarTrigger } from "@/Components/ui/sidebar";
import { Button } from "@/components/ui/button";
import { Icon } from "@iconify/vue";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Link } from "@inertiajs/vue3";
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
                    <DropdownMenu>
                        <DropdownMenuTrigger
                            as-child
                            class="outline-none cursor-pointer"
                        >
                            <Avatar class="h-8 w-8 flex items-center">
                                <AvatarImage
                                    src="https://dummyimage.com/300/09f/fff.png"
                                />
                                <AvatarFallback>User</AvatarFallback>
                            </Avatar>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="bg-gray-800 text-white">
                            <DropdownMenuLabel>Akun Saya</DropdownMenuLabel>
                            <DropdownMenuSeparator />
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
            </div>
        </div>
    </header>
</template>
