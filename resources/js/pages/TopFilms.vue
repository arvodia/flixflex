<template>
    <div class="flex overflow-x-scroll pb-10 hide-scroll-bar">
        <div class="flex flex-nowrap lg:ml-40 md:ml-20 ml-10 ">
            <div v-for="movie in movies.results" class="inline-block px-3">
                <div class="h-64 max-w-xs overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out">
                     <router-link :to="{name:'Film',params:{id:movie.id}}" href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <img :src="getImage(movie)" class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-l-lg" alt="">
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted} from 'vue';
    import useMovies from '../services/topMovieService.js';
    export default{
        setup() {
            const {movies, getMovies} = useMovies();
            onMounted(getMovies());
            return {
                movies,
                getMovies
            };
        },
        methods: {
            getImage(movie) {
                return 'https://image.tmdb.org/t/p/w500/' + movie.poster_path;
            }
        }
    }

</script>