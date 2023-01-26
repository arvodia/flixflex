<template>
    <h1 class="text-xl p-3">Films</h1>
    <div class="grid p-2 grid-cols-1 gap-1 md:grid-cols-3 md:gap-3">

        <div v-for="movie in movies.results" class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <img class="rounded-t-lg" :src="getImage(movie)" alt="" />
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ movie.title }}
                </h5>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                    {{ movie.overview.slice(0, 180) }}...
                </p>
                <router-link :to="{name:'Film',params:{id:movie.id}}" href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Read more
                    <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </router-link>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center mb-2">
        <div class="inline-flex mt-2 xs:mt-0">
            <!-- Buttons -->
            <button @click="prev_page()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                Prev
            </button>
            <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border-l border-gray-700 hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                {{ movies.page }} / {{ movies.total_pages }}
            </div>
            <button @click="next_page()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border-0 border-l border-gray-700 rounded-r hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                Next
                <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
    </div>

</template>

<script>
    import {onMounted} from 'vue';
    import useMovies from '../services/movieService.js';
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
            },
            next_page() {
                var page = Number(this.movies.page) + 1;
                if (page > this.movies.total_pages) {
                    page = this.movies.total_pages;
                }
                this.getMovies(page);
            },
            prev_page() {
                var page = Number(this.movies.page) - 1;
                if (1 > page) {
                    page = 0;
                }
                this.getMovies(page);
            }
        }
    }

</script>