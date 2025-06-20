<script setup>
import { SidebarTrigger } from "@/Components/ui/sidebar";
import { Button } from "@/Components/ui/button";
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
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const user = page.props.auth.user;

// Fungsi menghasilkan warna tailwind berdasarkan nama
function getColorFromName(name) {
    const colors = [
        "bg-red-500",
        "bg-orange-500",
        "bg-amber-500",
        "bg-yellow-500",
        "bg-lime-500",
        "bg-green-500",
        "bg-emerald-500",
        "bg-teal-500",
        "bg-cyan-500",
        "bg-sky-500",
        "bg-blue-500",
        "bg-indigo-500",
        "bg-violet-500",
        "bg-purple-500",
        "bg-pink-500",
        "bg-rose-500",
    ];

    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }

    const index = Math.abs(hash) % colors.length;
    return colors[index];
}

const avatarColor = getColorFromName(user.name);
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
                            <Avatar
                                class="h-8 w-8 flex items-center justify-center rounded-full overflow-hidden"
                            >
                                <AvatarImage
                                    v-if="user.avatar"
                                    :src="user.avatar"
                                />
                                <AvatarFallback
                                    :class="[
                                        avatarColor,
                                        'text-white font-bold w-full h-full flex items-center justify-center rounded-full',
                                    ]"
                                >
                                    {{
                                        user.name
                                            .split(" ")
                                            .map((n) => n[0])
                                            .join("")
                                            .toUpperCase()
                                    }}
                                </AvatarFallback>
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
