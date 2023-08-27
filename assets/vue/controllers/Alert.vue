<template>
    <div class="flex items-center justify-between py-3 px-5 bg-red-500 text-white rounded" v-show="error">
        {{ error }}
        <span @click="error = '';"
            class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </span>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    error: String
});
let error = ref(props.error);
</script>