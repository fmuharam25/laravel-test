import axios from "axios";
import router from "@/router";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_USER(state, value) {
            state.user = value;
        },
    },
    actions: {
        async login({ commit }, payload) {
            return await axios
                .post("/api/auth/login", payload)
                .then(({ data }) => {
                    commit("SET_USER", data.data);
                    commit("SET_AUTHENTICATED", true);
                    router.push({name:'dashboard'})
                })
                .catch(({response}) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                    return response
                });
        },
        logout({ commit }) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
        },
    },
};
