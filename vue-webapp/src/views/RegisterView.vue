<template>
    <div>
        <div class="card m-auto" style="max-width: 30rem">
            <div class="card-body">
                <b-form @submit="onSubmit">
                    <h3 class="text-center">Register</h3>
                    <b-form-group class="mb-3" id="input-group-1" label="Email address:" label-for="input-1">
                        <b-form-input id="input-1" v-model="form.email" type="email" required />
                        <span class="text-danger">
                            {{ errors.email ? errors.email.toString() : '' }}
                        </span>
                    </b-form-group>
                    <b-form-group class="mb-3" id="input-group-2" label="Name:" label-for="input-2">
                        <b-form-input id="input-2" v-model="form.name" required />
                    </b-form-group>
                    <b-form-group class="mb-3" id="input-group-2" label="Password:" label-for="input-2">
                        <b-form-input id="input-2" v-model="form.password" type="password" required />
                    </b-form-group>
                    <b-form-group class="mb-3" id="input-group-3" label="Confirm password:" label-for="input-3">
                        <b-form-input id="input-3" v-model="form.password_confirmation" type="password" required />
                        <span class="text-danger">
                            {{ errors.password ? errors.password.toString() : '' }}
                        </span>
                    </b-form-group>

                    <b-button class="w-100" type="submit" variant="dark">
                        <span v-if="isLoading">Please wait..</span>
                        <span v-else>Register</span>
                    </b-button>
                    <div class="text-center">
                        <p>or</p>
                        <router-link class="text-muted" to="/login">
                            Already Have an account? Sign in Here!
                        </router-link>
                    </div>
                </b-form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import axios from 'axios';

const REGISTER_FIELDS = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
};

export default {
    name: 'RegisterView',

    data() {
        return {
            form: Object.assign({}, REGISTER_FIELDS),
            isLoading: false,
            errors: {
                email: [],
                password: [],
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
                .post('/register', this.form)
                .then(() => {
                    this.$toasted.success('Successfully Registered');
                    setTimeout(() => {
                        this.authenticated(true);
                        this.$router.push({ name: 'home' });
                    }, 1000);
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        // Validation errors
                        this.errors = error.response.data.errors;
                    } else {
                        // Handle other errors
                        console.error(error);
                    }
                })
                .finally(() => (this.isLoading = false));
        },
    },
};
</script>
