<template>
    <div class="py-5 px-8">
        <form @submit.prevent="submitSurvey" class="container mx-auto">
            <div class="grid grid-cols-6 items-center">
                <div class="mr-4">
                    <img :src="`/uploads/photos/${survey.image}`" alt="" />
                </div>
                <div class="col-span-5">
                    <h1 class="text-3xl mb-3">{{ survey.title }}</h1>
                    <p class="text-gray-500 text-sm" v-html="survey.description"></p>
                </div>
            </div>

            <div v-if="surveyFinished" class="py-8 px-6 bg-emerald-400 text-white w-[600px] mx-auto">
                <div class="text-xl mb-3 font-semibold ">Thank you for participating in this survey.</div>
                <button @click="submitAnotherResponse" type="button"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Submit another response
                </button>
            </div>
            <div v-else>
                <hr class="my-3">
                <div v-for="(question, ind) of survey.questions" :key="question.id">
                    <QuestionViewer v-model="answers[question.id]" :question="question" :index="ind" />
                </div>

                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
  
<script setup>
import { computed, ref } from "vue";
import QuestionViewer from "../components/QuestionViewer.vue";
import axios from "axios";
import { useToast } from "vue-toastify";

const props = defineProps({
    survey: Object
});

const survey = computed(() => props.survey);
const surveyFinished = ref(false);
const answers = ref({});

function submitSurvey() {
    let data = new FormData();
    data.append('answers', JSON.stringify(answers.value));
    axios.post(`/survey/${survey.value.id}/answers/save`, data, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(response => {
            if (response.status === 202) {
                surveyFinished.value = true;
            }
        })
        .catch(e => {
            useToast().notify({ title: 'Error', body: e.response.data.error, type: "error" });
        });
}

function submitAnotherResponse() {
    answers.value = {};
    surveyFinished.value = false;
}
</script>
