<template>
    <div class="container">
        <b-navbar class="p-2" toggleable="lg">
            <b-navbar-brand href="#">
                <img class="img-fluid" alt="hmc-logo" :src="require('@/assets/logo.png')" style="max-width: 80px" />
            </b-navbar-brand>

            <!-- Left side menu items -->
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item to="/">Home</b-nav-item>
                    <b-nav-item to="/login" v-if="!isAuthenticated">Sign in</b-nav-item>
                    <b-nav-item to="/register" v-if="!isAuthenticated">Register</b-nav-item>
                    <b-nav-item @click="logout" v-else>Sign out</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import axios from 'axios';

export default {
    name: 'NavBar',

    methods: {
        ...mapActions({
            authenticated: 'auth/authenticated',
        }),

        logout() {
            axios.post('/logout').finally(() => {
                this.authenticated(false);
                this.$router.push({ name: 'login' });
            });
        },
    },

    computed: {
        ...mapState({
            isAuthenticated: (state) => state.auth.isAuthenticated,
        }),
    },
};
</script>
