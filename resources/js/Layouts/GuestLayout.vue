<script setup></script>

<template>
    <div
        class="black-grid-bg min-h-screen flex items-center justify-center p-4 text-gray-200"
    >
        <slot />
    </div>
</template>

<style scoped>
.black-grid-bg {
    background-color: #000;
    background-image: linear-gradient(
            to right,
            rgba(40, 40, 40, 0.3) 1px,
            transparent 1px
        ),
        linear-gradient(to bottom, rgba(40, 40, 40, 0.3) 1px, transparent 1px);
    background-size: 24px 24px;
    position: relative;
    overflow: hidden;
}

/* Vignette Effect */
.black-grid-bg::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(
        circle at center,
        transparent 40%,
        rgba(0, 0, 0, 0.9) 100%
    );
    pointer-events: none;
}

/* Spotlight di tengah */
.black-grid-bg::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    right: -50%;
    bottom: -50%;
    background: radial-gradient(
        circle at center,
        rgba(59, 130, 246, 0.05) 0%,
        transparent 70%
    );
    animation: pulse 8s infinite alternate;
    pointer-events: none;
}

@keyframes pulse {
    0% {
        opacity: 0.3;
    }
    100% {
        opacity: 0.6;
    }
}
</style>
