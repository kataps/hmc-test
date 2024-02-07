<template>
    <div class="container">
        <h1 class="fw-muted">Create post</h1>
        <b-form @submit="onSubmit">
            <b-form-group class="mb-3" id="input-group-1" label-for="input-1">
                <b-form-input id="input-1" v-model="form.title" type="text" placeholder="Title" required />
                <span class="text-danger">
                    {{ errors.title ? errors.title.toString() : '' }}
                </span>
            </b-form-group>
            <b-form-textarea
                id="textarea"
                v-model="form.content"
                placeholder="Post content"
                rows="3"
                max-rows="6"
                required
            >
            </b-form-textarea>
            <b-button class="mt-3" type="submit" variant="secondary">
                <span v-if="isLoading">Please wait..</span>
                <span v-else>Submit</span>
            </b-button>
        </b-form>
    </div>
</template>

<script>
'use strict';

import axios from 'axios';

const POST_FIELDS = {
    title: '',
    content: '',
};

export default {
    name: 'CreatePost',

    data() {
        return {
            form: Object.assign({}, POST_FIELDS),
            errors: {
                title: null,
                content: null,
            },
            isLoading: null,
        };
    },

    methods: {
        onSubmit(event) {
            event.preventDefault();
            this.isLoading = true;

            this.isLoading = true;

            axios
                .post('/api/posts', this.form)
                .then(() => {
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
