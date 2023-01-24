import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';

export default function useMovies() {

    const movie = ref([]);
    const movies = ref([]);

    const getMovie = async (id) => {
        let response = await apiPublic().get('/api/movie/' + id);
        movie.value = response.data;
    }
    const getMovies = async () => {
        let response = await apiPublic().get('/api/movies');
        movies.value = response.data.results;
    }

    return {
        movies,
        getMovies,
        getMovie,
        movie
    }
}