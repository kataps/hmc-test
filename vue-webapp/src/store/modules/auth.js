import axios from 'axios';

export default {
    namespaced: true,

    state: {
        isAuthenticated: false,
        user: {},
    },

    mutations: {
        setIsAuthenticated(state, value) {
            state.isAuthenticated = value;
        },

        setUser(state, user) {
            state.user = user;
        },
    },

    actions: {
        authenticated({ commit }, isAuth) {
            if (isAuth) {
                axios.get('/api/me').then(({ data }) => {
                    commit('setUser', data);
                });
            }

            commit('setIsAuthenticated', isAuth);
        },
    },

    getters: {
        isAuthenticated: (state) => state.isAuthenticated,
    },
};
