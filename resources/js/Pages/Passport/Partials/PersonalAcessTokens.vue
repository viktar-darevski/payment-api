<template>
    <div class="flex flex-col space-y-4">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Access Tokens</h3>
                <button @click="showCreateTokenForm = true" class="text-indigo-600 hover:text-indigo-500">Create New Token</button>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Scopes
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Delete</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="token in tokens">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ token.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ token.scopes ? token.scopes.join(', ') : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            {{ token.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="revoke(token)" class="text-red-600 hover:text-red-500">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="showCreateTokenForm" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-1/3">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Create New Token
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Please enter the details for the new token.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form class="space-y-6">
                            <div v-if="form.errors.length" class="mt-2">
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">There were some errors with your submission</strong>
                                    <ul>
                                        <li v-for="error in form.errors">{{ error }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" v-model="form.name" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="scopes" class="block text-sm font-medium text-gray-700">Scopes</label>
                                <div class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div v-for="scope in scopes" :key="scope.id" class="flex items-center">
                                        <input type="checkbox" :id="scope.id" :value="scope.id" v-model="form.scopes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <label :for="scope.id" class="ml-2 block text-sm text-gray-900">{{ scope.description }}</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" @click="store">
                            Create
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showCreateTokenForm = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showAccessTokenModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-1/3">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Access Token
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Please copy your new personal access token. For your security, it won't be shown again.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <input type="text" readonly :value="accessToken" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <button class="ml-2 text-indigo-600 hover:text-indigo-500" @click="copyAccessToken">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" @click="showAccessTokenModal = false">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import axios from 'axios';

const accessToken = ref(null);
const tokens = ref([]);
const scopes = ref([]);
const form = ref({
    name: '',
    scopes: [],
    errors: []
});
const showCreateTokenForm = ref(false);
const showAccessTokenModal = ref(false);


const getTokens = () => {
    axios.get("/oauth/personal-access-tokens").then(e => {
        tokens.value = e.data;
    });
};

const getScopes = () => {
    axios.get("/oauth/scopes").then(e => {
        scopes.value = e.data;
    });
};

const prepareComponent = () => {
    getTokens();
    getScopes();
};

const store = () => {
    accessToken.value = null;
    form.value.errors = [];
    axios.post("/oauth/personal-access-tokens", form.value).then(e => {
        form.value.name = "";
        form.value.scopes = [];
        form.value.errors = [];
        tokens.value.push(e.data.token); // Add the new token to the tokens array
        showAccessToken(e.data.accessToken);
    }).catch(e => {
        if (typeof e.response.data === "object") {
            form.value.errors = Object.values(e.response.data.errors).flat();
        } else {
            form.value.errors = ["Something went wrong. Please try again."];
        }
    });
};

const showAccessToken = (e) => {
    showCreateTokenForm.value = false;
    accessToken.value = e;
    showAccessTokenModal.value = true;
};

const copyAccessToken = () => {
    navigator.clipboard.writeText(accessToken.value);
};

const revoke = (e) => {
    axios.delete("/oauth/personal-access-tokens/" + e.id).then(e => {
        getTokens();
    });
};

onMounted(() => {
    prepareComponent();
    nextTick(() => {
        const input = document.getElementById('name');
        if (input) {
            input.focus();
        }
    });
});
</script>

