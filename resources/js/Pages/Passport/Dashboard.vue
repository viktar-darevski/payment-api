<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Clients from './Partials/Clients.vue';
import PersonalAccessTokens from './Partials/PersonalAcessTokens.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Passport Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Passport Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <Clients/>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <PersonalAccessTokens/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
