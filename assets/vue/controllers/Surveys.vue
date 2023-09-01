<template>
    <PageComponent title="Survey">
        <template v-slot:header>
            <a href="/survey/create" class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -mt-1 inline-block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add new Survey
            </a>
        </template>
        <div v-if="surveys.length">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
                <SurveyListItem v-for="(survey, ind) in surveys" :key="survey.id" :survey="survey"
                    class="animate-fade-in-down" :style="{ animationDelay: `${ind * 0.1}s` }"
                    @delete="deleteSurvey(survey)" />
            </div>
        </div>
        <div v-else class="text-gray-600 text-center py-16">
            Your don't have surveys yet
        </div>
    </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import SurveyListItem from '../components/SurveyListItem.vue';
import useSurvey from '../composables/survey';

const props = defineProps({
    surveys: Array
});

function deleteSurvey(survey) {
    useSurvey().deleteSurvey(survey);
}
</script>
