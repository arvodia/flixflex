import { defineStore } from 'pinia';

const defaulValues = {
    name: 'userName',
};

const useUserStore = defineStore('user', {
    state: () => ({
            userData: defaulValues,
            isAuth: false,
        }),
    getters: {
        getValues: function (state) {
            return {
                user_name: state.userData.name,
            }
        },
    },
    actions: {
        resetState() {
            this.userData = defaulValues;
            this.isAuth = false;
        },
        updateData: function (data) {
            for (var item in this.userData) {
                if (typeof data[item] !== 'undefined') {
                    this.userData[item] = data[item];
                }
            }
        },
        setAuth: function (token) {
            this.isAuth = true;
        },
    }
});

export default useUserStore;