<script setup lang="ts">
import { AlignLeft, ChevronDown, MountainSnow } from "lucide-vue-next";
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
        url: "#",
        icon: MountainSnow,
    },
    {
        title: "Genres",
        url: "#",
        icon: AlignLeft,
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
            <CollapsibleRoot defaultOpen class="group/collapsible">
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
                                <SidebarMenuButton asChild>
                                    <Link :href="route('dashboard')">
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
                                        :is-active="route().current(item.url)"
                                    >
                                        <Link :href="item.url">
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
        </SidebarContent>
    </Sidebar>
</template>
