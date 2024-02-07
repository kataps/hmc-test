<template>
    <div class="container">
        <div v-if="post" class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <img
                        class="rounded-circle shadow-4-strong mr-3"
                        alt="avatar2"
                        :src="require('@/assets/user-dp.png')"
                        style="max-width: 50px"
                    />
                    <h4 class="text-muted">{{ post.user.name }}</h4>
                </div>
                <div>
                    <b-dropdown size="sm" text="actions" class="m-2" v-if="isPostOwner">
                        <router-link
                            :to="{
                                name: 'posts.edit',
                                params: { id: post.id },
                            }"
                            class="text-decoration-none"
                        >
                            <b-dropdown-item-button>
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                                Edit
                            </b-dropdown-item-button>
                        </router-link>
                        <b-dropdown-item-button @click="confirmDeletePost">
                            <font-awesome-icon :icon="['far', 'trash-can']" />
                            Delete
                        </b-dropdown-item-button>
                    </b-dropdown>
                </div>
            </div>
            <div class="card-body">
                <h1>{{ post.title }}</h1>
                <p>{{ post.content }}</p>
            </div>
        </div>
        <hr />
        <b-form @submit="submitComment">
            <b-form-textarea
                id="textarea"
                v-model="form.content"
                placeholder="Join the discussion"
                rows="3"
                max-rows="6"
                required
            >
            </b-form-textarea>
            <div class="w-100 d-flex flex-row-reverse">
                <b-button class="mt-3 ml-auto" type="submit" variant="primary">
                    <span v-if="isLoading">Please wait..</span>
                    <span v-else>Comment</span>
                </b-button>
            </div>
        </b-form>

        <div class="container" v-if="comments">
            <h6 class="text-muted fw-bold mb-5">comments ({{ commentsRaw.total }})</h6>
            <div class="row mb-2 p-4 border-1" v-for="(comment, index) in comments" :key="index">
                <div class="col-12 col-md-1">
                    <img
                        class="rounded-circle shadow-4-strong mr-3"
                        alt="avatar2"
                        :src="require('@/assets/user-dp.png')"
                        style="max-width: 50px"
                    />
                </div>
                <div class="col-12 col-md-11">
                    <div class="d-flex gap-2 align-items-center">
                        <span>
                            <strong>
                                {{ comment.user.name }}
                            </strong>
                        </span>
                        <span>{{ humanizeTimeFormat(comment.created_at) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>{{ comment.content }}</p>
                        <div class="d-flex justify-content-between" v-if="isPostOwner || isCommentOwner(comment)">
                            <button
                                class="btn btn-default text-primary"
                                @click="openModal(comment.content, comment.id)"
                                v-if="isCommentOwner(comment)"
                            >
                                <span class="mr-4"><font-awesome-icon :icon="['far', 'pen-to-square']" />Edit</span>
                            </button>

                            <button class="btn btn-default text-danger" @click="confirmDeleteComment(comment.id)">
                                <span><font-awesome-icon :icon="['far', 'trash-can']" /> Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <comment-edit-modal ref="modal" @comment-save="getComments" />
    </div>
</template>

<script>
'use strict';

import { mapState } from 'vuex';
import axios from 'axios';
import moment from 'moment';
import CommentEditModal from './comment/CommentEditModal';

const FORM_COMMENT_FIELD = {
    content: null,
};

export default {
    name: 'PostDetails',

    components: {
        CommentEditModal,
    },

    data() {
        return {
            post: null,
            form: Object.assign({}, FORM_COMMENT_FIELD),
            isLoading: false,
            commentsRaw: null,
            comments: null,
        };
    },

    methods: {
        isCommentOwner(comment) {
            return comment.user_id === this.loggedUser.id;
        },

        openModal(content, commentId) {
            this.$refs.modal.showModal(content, commentId);
        },

        submitComment(event) {
            event.preventDefault();
            this.isLoading = true;
            let data = this.form;
            data.post_id = this.postId();

            axios
                .post('/api/comments', data)
                .then(() => {
                    this.getComments();
                    this.form = Object.assign({}, FORM_COMMENT_FIELD);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        humanizeTimeFormat(dateTime) {
            let now = moment();
            let createdAtMoment = moment(dateTime);

            return moment.duration(createdAtMoment.diff(now)).humanize(true);
        },

        postId() {
            return this.$route.params.id;
        },

        getPost() {
            axios.get('/api/posts/' + this.postId()).then(({ data }) => {
                this.post = data;
                this.getComments();
            });
        },

        getComments() {
            const url = '/api/posts/' + this.postId() + '/comments';
            axios.get(url).then(({ data }) => {
                this.commentsRaw = data;
                this.comments = this.commentsRaw.data;
            });
        },

        deletePost() {
            axios.delete('/api/posts/' + this.postId()).then(() => {
                this.$swal.fire('Deleted!', 'Your Post has been deleted.', 'success');
                this.$router.push({ name: 'home' });
            });
        },

        deleteComment(commentId) {
            axios.delete('/api/comments/' + commentId).then(() => {
                this.getComments();
            });
        },

        confirmDeletePost() {
            this.$swal
                .fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this post!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.deletePost();
                    }
                });
        },

        confirmDeleteComment(commentId) {
            this.$swal
                .fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this comment!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.deleteComment(commentId);
                    }
                });
        },
    },

    computed: {
        isPostOwner() {
            let isAuthor = false;

            if (this.post) {
                isAuthor = this.post.user.id === this.loggedUser.id;
            }

            return isAuthor;
        },

        ...mapState({
            loggedUser: (state) => state.auth.user,
        }),
    },

    mounted() {
        this.getPost();
    },
};
</script>
