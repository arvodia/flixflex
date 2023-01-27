<template>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Create and account
                    </h1>
                    <div v-show="msg_error" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">{{ msg_error }}</span>
                    </div>
                    <form class="space-y-4 md:space-y-6" @submit.prevent="formSubmit">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input v-model="email" type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
                        </div>
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your name</label>
                            <input v-model="name" type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input v-model="password" type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        </div>
                        <div>
                            <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                            <input v-model="password_confirmation" type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input v-model="terms" id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required="">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="#">Terms and Conditions</a></label>
                            </div>
                        </div>
                        <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Create an account
                        </button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Already have an account? 
                        <router-link to="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                            Login here
                        </router-link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import apiPublic from '../api/apiPublic.js';
    import apiPrivate from '../api/apiPrivate.js';
    import useUserStore from '../stores/user.js';
    export default {
        name: "register",
        data() {
            return {
                loading: true,
                msg_error: '',
                email: '',
                name: '',
                password: '',
                password_confirmation: '',
                terms: '',

            };
        },
        methods: {
            formSubmit() {
                this.msg_error = '';
                if (!this.email || !this.name || !this.password || !this.password_confirmation) {
                    this.msg_error = 'veuillez remplir tous les champs du formulaire';
                    return;
                }
                if (!this.terms) {
                    this.msg_error = 'veuillez accepter les conditions';
                    return;
                }
                if (this.password !== this.password_confirmation) {
                    this.msg_error = 'erreur confirmation mot de passe';
                    return;
                }
                const store = useUserStore();
                apiPublic().post('/api/register', {
                    email: this.email,
                    name: this.name,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    terms: this.terms,
                })
                        .then((response) => {
                            if (response.data && response.data.token) {
                                sessionStorage.setItem("token", response.data.token);
                                return response.data.token;
                            } else {
                                this.msg_error = 'impossible de créer une connexion !';
                            }
                        })
                        .then((token) => apiPrivate(token).get('/api/account'))
                        .then((response) => {
                            if (response.data && response.data.data.name) {
                                store.updateData({name: response.data.data.name});
                                store.setAuth();
                                this.$router.push('/');
                            } else {
                                this.msg_error = 'impossible de créer une connexion !';
                                store.resetState();
                            }
                        })
                        .catch((error) => {
                            if (error && error.response && error.response.data && error.response.data.message) {
                                this.msg_error = error.response.data.message;
                            } else {
                                this.msg_error = 'Error : impossible de créer un identifiant ';
                            }
                        });
            },
        },
        computed: {}
    };
</script>