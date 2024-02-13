<template>
        <div class="flex flex-col space-y-4">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">OAuth Clients</h3>
                    <button @click="showCreateClientForm = true" class="text-indigo-600 hover:text-indigo-500">Create New Client</button>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Redirect URL
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Secret
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Delete</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="client in clients" class="whitespace-nowrap">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ client.id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ client.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ client.redirect }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><code>{{ client.secret ? client.secret : '-' }}</code></td>
                        <td class="px-6 py-4 text-right text-sm font-medium"><button @click="destroy(client)" class="text-red-600 hover:text-red-500">Delete</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="showCreateClientForm">
            <div class="fixed inset-0 overflow-y-auto z-10" role="dialog" aria-labelledby="modal-title" aria-modal="true">
                <div class="px-4 justify-center flex items-end min-h-screen pb-20 pt-4 sm:block sm:p-0 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="sm:align-middle hidden sm:h-screen sm:inline-block" aria-hidden="true">​</span>
                    <div class="bg-white rounded-lg align-bottom inline-block overflow-hidden shadow-xl sm:align-middle sm:my-8 sm:w-1/3 text-left transform transition-all">
                        <div class="px-4 bg-white pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:items-start sm:flex">
                                <div class="sm:ml-4 mt-3 sm:mt-0 sm:text-left text-center">
                                    <h3 class="font-medium text-gray-900 leading-6 text-lg" id="modal-title">Create New Client</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Please enter the details for the new client.</p>
                                    </div>
                                </div>
                            </div>
                            <form class="space-y-6">
                                <div class="mt-2" v-if="createForm.errors.length">
                                    <div class="px-4 border bg-red-100 border-red-400 py-3 relative rounded text-red-700" role="alert">
                                        <strong class="font-bold">There were some errors with your submission</strong>
                                        <ul>
                                            <li v-for="error in createForm.errors">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <label class="font-medium text-sm block text-gray-700" for="name">Name</label>
                                    <input class="block border-gray-300 rounded-md shadow-sm sm:text-sm w-full mt-1" v-model="createForm.name" id="name">
                                </div>
                                <div>
                                    <label class="font-medium text-sm block text-gray-700" for="redirect">Redirect URL</label>
                                    <input class="block border-gray-300 rounded-md shadow-sm sm:text-sm w-full mt-1" v-model="createForm.redirect" id="redirect">
                                </div>
                            </form>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 sm:flex sm:flex-row-reverse sm:px-6">
                            <button @click="store" class="font-medium border focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex justify-center px-4 py-2 rounded-md shadow-sm sm:ml-3 sm:text-sm sm:w-auto text-base w-full bg-indigo-600 border-transparent hover:bg-indigo-700 text-white" type="button">Create</button>
                            <button @click="showCreateClientForm = false" class="font-medium border focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex justify-center px-4 py-2 rounded-md shadow-sm sm:ml-3 sm:text-sm sm:w-auto text-base w-full bg-white border-gray-300 hover:bg-gray-50 mt-3 sm:mt-0 text-gray-700" type="button">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const clients = ref([]);
const createForm = ref({
    errors: [],
    name: '',
    redirect: ''
});
const showCreateClientForm = ref(false);

const getClients = () => {
    axios.get('/oauth/clients').then(response => {
        clients.value = response.data;
    });
};

const store = () => {
    persistClient('post', '/oauth/clients', createForm);
};

const persistClient = (method, uri, form) => {
    form.value.errors = [];
    axios.post("/oauth/clients", form.value).then(e => {
        getClients();
        form.value.name = '';
        form.value.redirect = '';
        form.value.errors = [];
        showCreateClientForm.value = false
    }).catch(error => {
        if (typeof error.response.data === 'object') {
            form.value.errors = Object.values(error.response.data.errors).flat();
        } else {
            form.value.errors = ['Something went wrong. Please try again.'];
        }
    });
};

const destroy = (client) => {
    axios.delete('/oauth/clients/' + client.id).then(() => {
        getClients();
    });
};

const prepareComponent = () => {
    getClients();
};

onMounted(() => {
    prepareComponent();
});

</script>
