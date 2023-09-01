<template>
    <PageComponent :title="survey.id ? survey.title : 'Create a Survey'">
        <template v-slot:header>
            <div class="flex">
                <a :href="`#`" target="_blank" v-if="survey.slug"
                    class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    View Public link
                </a>
                <button v-if="survey.id" type="button" @click="deleteSurvey"
                    class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -mt-1 inline-block" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Delete Survey
                </button>
            </div>
        </template>
        <form @submit.prevent="saveSurvey" class="animate-fade-in-down">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <!-- Survey Fields -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <div class="mt-1 flex items-center">
                            <img v-if="survey.image" :src="imageUrl" :alt="survey.title" class="w-64 h-48 object-cover" />
                            <span v-else
                                class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <button type="button"
                                class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <input type="file" @change="onImageChoose"
                                    class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer" />
                                Change
                            </button>
                        </div>
                    </div>
                    <!--/ Image -->

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" v-model="survey.title" autocomplete="survey_title"
                            placeholder="Survey Title"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                    <!--/ Title -->

                    <!-- Description -->
                    <div>
                        <label for="about" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" v-model="survey.description"
                                autocomplete="survey_description"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Describe your survey" />
                        </div>
                    </div>
                    <!-- Description -->

                    <!-- Expire Date -->
                    <div>
                        <label for="expire_date" class="block text-sm font-medium text-gray-700">Expire Date</label>
                        <input type="date" name="expire_date" id="expire_date" v-model="survey.expire_date"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                    <!--/ Expire Date -->

                    <!-- Status -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="status" name="status" type="checkbox" v-model="survey.status"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status" class="font-medium text-gray-700">Active</label>
                        </div>
                    </div>
                    <!--/ Status -->
                </div>
                <!--/ Survey Fields -->

                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3 class="text-2xl font-semibold flex items-center justify-between">
                        Questions

                        <!-- Add new question -->
                        <button type="button" @click="addQuestion"
                            class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Add Question
                        </button>
                        <!--/ Add new question -->
                    </h3>
                    <div v-if="!survey.questions.length" class="text-center text-gray-600">
                        You don't have any questions created
                    </div>
                    <div v-for="(question, index) in survey.questions" :key="question.id">
                        <QuestionEditor :question="question" :index="index" @change="questionChange"
                            @deleteQuestion="deleteQuestion" />
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useToast, useVtEvents } from 'vue-toastify';
import PageComponent from '../components/PageComponent.vue';
import QuestionEditor from '../components/QuestionEditor.vue';
import axios from 'axios';
import { v4 as uuidv4 } from "uuid";
import useSurvey from '../composables/survey';

const props = defineProps({
    survey: {
        type: Object,
        default: {
            id: null,
            title: "",
            slug: "",
            status: false,
            description: null,
            image: null,
            expire_date: null,
            questions: [],
        }
    }
});

let survey = reactive(props.survey);
let imageUrl = ref(survey.image ? '/uploads/photos/' + survey.image : '');

function saveSurvey() {
    let data = new FormData();
    for (const key in survey) {
        data.append(key, key === 'questions' ? JSON.stringify(survey[key]) : survey[key]);
    }
    axios.post('/surveys/store', data, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(response => {
            let action = survey.id ? 'updated' : 'created';
            useToast().notify({ body: `The survey was successfully ${action}`, type: "success" })
            useVtEvents().once('vtDismissed', () => {
                window.location = `/surveys/${response.data.id}`;
            });
        })
        .catch(e => {
            useToast().notify({ title: 'Error', body: e.response.data.error, type: "error" });
        });
}

function onImageChoose(e) {
    // The field to send on backend and apply validations
    survey.image = e.target.files[0];

    const reader = new FileReader();
    reader.onload = () => {
        // The field to display here
        imageUrl.value = reader.result;
        e.target.value = "";
    };
    reader.readAsDataURL(survey.image);
}

function deleteSurvey(survey) {
    useSurvey().deleteSurvey(survey);
}

function addQuestion() {
    const newQuestion = {
        id: uuidv4(),
        type: "text",
        question: "",
        description: null,
        data: {
            options: []
        },
    };

    survey.questions.push(newQuestion);
}

function deleteQuestion(question) {
    survey.questions = survey.questions.filter((q) => q !== question);
}

function questionChange(question) {
    // Important to explicitelly assign question.data.options, because otherwise it is a Proxy object
    // and it is lost in JSON.stringify()
    if (question.data.options) {
        question.data.options = [...question.data.options];
    }
    survey.questions = survey.questions.map((q) => {
        if (q.id === question.id) {
            return JSON.parse(JSON.stringify(question));
        }
        return q;
    });
}
</script>