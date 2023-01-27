<template>
    <h1 class="text-xl p-3">Film</h1>


    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <img v-if="movie.poster_path" class="rounded-t-lg" :src="getImage(movie)" alt="" />
        <div class="p-5">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ movie.title }}
            </h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                {{ movie.overview }}...
            </p>
            <div v-if="movie.trailer">

                <h2 class="text-lg p-3">trailer</h2>
                <iframe v-if="movie.trailer" width="420" height="315"
                        :src="'https://www.youtube.com/embed/'+movie.trailer">
                </iframe> 
            </div>
            <div v-if="store.isAuth" class="mt-2">
                <button v-if="movie.is_favorie" @click="remove_favorie(movie.id)" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    remove favorie
                    <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <button v-else @click="add_favorie(movie.id)"  class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-blue-green focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    add favorie
                    <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div v-else class="mt-2">
                <router-link to="/login" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-blue-green focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Login
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted} from 'vue';
    import useMovies from '../services/movieService.js';
    import useUserStore from '../stores/user.js';
    export default{
        props: {
            id: {
                required: true,
                type: String
            }
        },
        setup(props) {
            const {movie, getMovie, addFavorie, removeFavorie} = useMovies();
            // 

            onMounted(getMovie(props.id));

            const add_favorie = async () => {
                await addFavorie(props.id);
                getMovie(props.id);
            }

            const remove_favorie = async () => {
                await removeFavorie(props.id);
                getMovie(props.id);
            }

            const store = useUserStore();

            return {
                movie,
                add_favorie,
                remove_favorie,
                store
            };
        },
        methods: {
            getImage(movie) {
                return 'https://image.tmdb.org/t/p/w500/' + movie.poster_path;
            }
        }
    }

</script>