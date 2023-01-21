var api_private = null;
export default function apiPrivate(token) {
    if (token) {
        // auto create new after next condition
    } else if (api_private) {
        return api_private;
    }
    api_private = axios.create({
        withCredentials: true,
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Authorization: 'Bearer ' + token,
        }
    });
    return api_private;
}
;