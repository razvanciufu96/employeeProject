<template>
    <div :class="['notification', type]" :style="{ animationDuration: `${duration / 1000}s` }">
        <span>{{ message }}</span>
    </div>
</template>

<script>
export default {
    props: {
        message: String,
        type: {
            type: String,
            default: 'success',
        },
        duration: {
            type: Number,
            default: 3000,
        },
    },
};
</script>

<style scoped>
.notification {
    padding: 10px;
    margin: 10px;
    border-radius: 5px;
    position: fixed;
    top: 10px;
    right: 10px;
    width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    animation: fadeInOut 1s forwards;
}

.notification.success {
    background-color: #28a745;
    color: white;
}

.notification.error {
    background-color: #dc3545;
    color: white;
}

@keyframes fadeInOut {
    0% {
        opacity: 0;
        top: -20px;
    }
    50% {
        opacity: 1;
        top: 10px;
    }
    100% {
        opacity: 0;
        top: -20px;
    }
}
</style>
