import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';
import apiPrivate from '../api/apiPrivate.js';

export default function useMovies() {

    const movie = ref([]);
    const movies = ref([]);

    const getMovie = async (id) => {
        let response = await apiPublic().get('/api/movie/' + id);
        movie.value = response.data;
    }

    const addFavorie = async (id) => {
        let response = await apiPrivate().get('/api/favorie/' + id + '/add');
    }

    const removeFavorie = async (id) => {
        let response = await apiPrivate().get('/api/favorie/' + id + '/remove');
    }

    const getMovies = async (page) => {
        page = page ? page : 1;
        let response = await apiPublic().get('/api/movies?page=' + page);
        movies.value = response.data;
    }

    return {
        movies,
        getMovies,
        getMovie,
        movie,
        addFavorie,
        removeFavorie
    }
}