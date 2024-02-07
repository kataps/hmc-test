<template>
    <div class="container p-4">
        <h1 class="fw-bold text-center">handlemycomplaint.com.au</h1>
        <p v-if="!isAuthenticated" class="text-center">
            To view posts and comments, Please
            <router-link to="/login">Sign-In</router-link> or Become a member by creating an account
            <router-link to="/register">here</router-link>.
        </p>
        <div v-else>
            <div class="d-flex flex-row-reverse mb-4 w-100">
                <router-link to="/posts/create">
                    <button class="btn btn-success">ADD POST</button>
                </router-link>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 mb-4" v-for="(post, index) in posts" :key="index">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ post.title }}</h4>
                            <p class="card-text">
                                {{ truncateContent(post.content) }}.
                                <br />
                                <router-link
                                    :to="{
                                        name: 'posts.details',
                                        params: { id: post.id },
                                    }"
                                >
                                    See more
                                </router-link>
                            </p>
                            <span class="text-muted">
                                Author: {{ post.user.name }} <br />
                                {{ formatDataTime(post.created_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pagination mt-4" style="gap: 4px" v-if="rawResponse">
                <li class="page-item" :class="{ disabled: rawResponse.current_page === 1 }">
                    <a class="page-link" @click="getPosts(rawResponse.current_page - 1)">Previous</a>
                </li>
                <li
                    v-for="page in rawResponse.last_page"
                    class="page-item"
                    :key="page"
                    :class="{ active: rawResponse.current_page === page }"
                >
                    <a class="page-link" @click="getPosts(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{ disabled: rawResponse.current_page === rawResponse.last_page }">
                    <a class="page-link" @click="getPosts(rawResponse.current_page + 1)">Next</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';
import moment from 'moment';

export default {
    name: 'HomeView',

    data() {
        return {
            posts: [],
            rawResponse: null,
        };
    },

    methods: {
        async getPosts(page = 1) {
            await axios.get('/api/posts?page=' + page).then(({ data }) => {
                this.rawResponse = data;
                this.posts = data.data;
            });
        },

        truncateContent(content, maxLength = 60) {
            if (content.length > maxLength) {
                return content.substring(0, maxLength) + '...';
            }

            return content;
        },

        formatDataTime(dateTime) {
            dateTime = moment(dateTime).format('LLL');

            return dateTime;
        },
    },

    computed: {
        ...mapState({
            isAuthenticated: (state) => state.auth.isAuthenticated,
        }),
    },

    mounted() {
        if (this.isAuthenticated) {
            this.getPosts();
        }
    },
};
</script>
