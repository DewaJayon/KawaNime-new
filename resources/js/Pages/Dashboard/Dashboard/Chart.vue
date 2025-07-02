<script setup>
import { usePage } from "@inertiajs/vue3";
import VueApexCharts from "vue3-apexcharts";

const page = usePage();

const animePerMonth = page.props.animePerMonth;
const month = page.props.month;

const series = [
    {
        name: "Jumlah Anime",
        data: animePerMonth,
    },
];

const chartOptions = {
    chart: {
        height: 350,
        type: "area",
        toolbar: {
            show: false,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: "smooth",
    },
    yaxis: {
        labels: {
            style: {
                colors: "#fff",
            },
        },
    },
    xaxis: {
        categories: month,
        labels: {
            style: {
                colors: "#fff",
            },
        },
    },
    stroke: {
        curve: "smooth",
    },
    tooltip: {
        theme: "dark",
    },
    title: {
        text: "Pertumbuhan Anime per Bulan",
        align: "left",
        style: {
            fontSize: "20px",
            color: "#fff",
            fontFamily:
                "Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
            fontWeight: "bold",
        },
    },
    colors: ["#00e396"],
};
</script>

<template>
    <VueApexCharts
        class="text-white"
        :options="chartOptions"
        :series="series"
    />
</template>
