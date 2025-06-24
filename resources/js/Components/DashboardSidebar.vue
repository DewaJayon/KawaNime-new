<script setup lang="ts">
import { AlignLeft, ChevronDown, MountainSnow, User } from "lucide-vue-next";
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/Components/ui/sidebar";
import {
    CollapsibleContent,
    CollapsibleRoot,
    CollapsibleTrigger,
} from "reka-ui";
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

// Menu items.
const generalItems = [
    {
        title: "Banners",
        routeName: "banner.index",
        icon: MountainSnow,
    },
    {
        title: "Genres",
        routeName: "genre.index",
        icon: AlignLeft,
    },
    {
        title: "Users",
        routeName: "user.index",
        icon: User,
    },
];
</script>

<template>
    <Sidebar>
        <SidebarHeader>
            <h1 class="text-2xl font-bold flex items-center justify-center">
                Kawa<span class="text-accent">Nime</span>
            </h1>
        </SidebarHeader>
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Dashboard</SidebarGroupLabel>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton
                                asChild
                                :is-active="route().current('dashboard')"
                            >
                                <Link :href="route('dashboard')">
                                    <Icon
                                        icon="material-symbols:dashboard"
                                        width="24"
                                        height="24"
                                    />
                                    Dashboard</Link
                                >
                            </SidebarMenuButton>
                            <SidebarMenuButton
                                asChild
                                :is-active="
                                    route().current('dashboard.anime.*') ||
                                    route().current('dashboard.episode.*')
                                "
                            >
                                <Link :href="route('dashboard.anime.index')">
                                    <Icon
                                        icon="material-symbols:movie"
                                        width="24"
                                        height="24"
                                    />
                                    Anime
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>

            <CollapsibleRoot defaultOpen class="group/collapsible">
                <SidebarGroup>
                    <SidebarGroupLabel asChild>
                        <CollapsibleTrigger>
                            General
                            <ChevronDown
                                class="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-180"
                            />
                        </CollapsibleTrigger>
                    </SidebarGroupLabel>
                    <CollapsibleContent>
                        <SidebarGroupContent>
                            <SidebarMenu>
                                <SidebarMenuItem
                                    v-for="item in generalItems"
                                    :key="item.title"
                                >
                                    <SidebarMenuButton
                                        asChild
                                        :is-active="
                                            route().current(item.routeName)
                                        "
                                    >
                                        <Link
                                            :href="
                                                route().has(item.routeName)
                                                    ? route(item.routeName)
                                                    : '#'
                                            "
                                        >
                                            <component :is="item.icon" />
                                            <span>{{ item.title }}</span>
                                        </Link>
                                    </SidebarMenuButton>
                                </SidebarMenuItem>
                            </SidebarMenu>
                        </SidebarGroupContent>
                    </CollapsibleContent>
                </SidebarGroup>
            </CollapsibleRoot>

            <SidebarGroup>
                <SidebarGroupLabel>Lainnya...</SidebarGroupLabel>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton asChild>
                                <a :href="route('home')" target="_blank">
                                    <Icon
                                        icon="streamline-plump:web"
                                        width="24"
                                        height="24"
                                    />
                                    Lihat Website</a
                                >
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
    </Sidebar>
</template>
