var api_public = null;
export default function apiPublic() {
    if (api_public) {
        return api_public;
    }
    return (api_public = axios.create({
        withCredentials: true,
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }));
}