<template>
    <div class="container">
        <h1 class="fw-muted">Edit Post</h1>
        <b-form @submit="onSubmit">
            <b-form-group class="mb-3" id="input-group-1" label="Title:" label-for="input-1">
                <b-form-input id="input-1" v-model="form.title" type="text" placeholder="Title" required />
                <span class="text-danger">
                    {{ errors.title ? errors.title.toString() : '' }}
                </span>
            </b-form-group>
            <label for="textarea">Content:</label>
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
                <span v-else>Save</span>
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
    name: 'PostEdit',

    data() {
        return {
            form: Object.assign({}, POST_FIELDS),
            errors: {
                title: null,
                content: null,
            },
            isLoading: false,
        };
    },

    methods: {
        postId() {
            return this.$route.params.id;
        },
        onSubmit(event) {
            event.preventDefault();
            this.isLoading = true;

            axios
                .put('/api/posts/' + this.postId(), this.form)
                .then(() => {
                    this.$router.push({
                        name: 'posts.details',
                        params: { id: this.postId() },
                    });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => (this.isLoading = false));
        },

        getPost() {
            axios.get('/api/posts/' + this.postId()).then(({ data }) => {
                this.form.title = data.title;
                this.form.content = data.content;
            });
        },
    },

    mounted() {
        this.getPost();
    },
};
</script>
