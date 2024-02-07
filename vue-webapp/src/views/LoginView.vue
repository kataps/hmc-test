<template>
    <div>
        <div class="card m-auto" style="max-width: 30rem">
            <div class="card-body">
                <b-form @submit="onSubmit">
                    <h3 class="text-center">Sign in</h3>
                    <b-form-group class="mb-3" id="input-group-1" label="Email address:" label-for="input-1">
                        <b-form-input id="input-1" v-model="form.email" type="email" required />
                        <span class="text-danger">
                            {{ errors.email ? errors.email.toString() : '' }}
                        </span>
                    </b-form-group>
                    <b-form-group class="mb-3" id="input-group-2" label="Password:" label-for="input-2">
                        <b-form-input id="input-2" v-model="form.password" type="password" required />
                    </b-form-group>
                    <b-button class="w-100" type="submit" variant="primary">
                        <span v-if="isLoading">Please wait..</span>
                        <span v-else>Login</span>
                    </b-button>
                    <div class="text-center">
                        <p>or</p>
                        <router-link class="text-muted" to="/register"> Not a member yet? Register Here!</router-link>
                    </div>
                </b-form>
            </div>
        </div>
    </div>
</template>
<script>
'use strict';

import axios from 'axios';
import { mapActions } from 'vuex';

const LOGIN_FIELDS = {
    email: null,
    password: null,
};

export default {
    name: 'LoginView',

    data() {
        return {
            form: Object.assign({}, LOGIN_FIELDS),
            isLoading: false,
            errors: {
                email: null,
            },
        };
    },

    methods: {
        ...mapActions({
            authenticated: 'auth/authenticated',
        }),

        onSubmit(event) {
            event.preventDefault();
            this.isLoading = true;

            axios
                .post('/login', this.form)
                .then(() => {
                    this.authenticated(true);
                    this.$router.push({ name: 'home' });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => (this.isLoading = false));
        },
    },
};
</script>
