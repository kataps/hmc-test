<template>
    <div>
        <b-modal
            id="commentEditModal"
            :hide-header-close="true"
            @hidden="reset()"
            :hide-footer="true"
            ref="commentEditModal"
        >
            <b-form @submit="saveComment">
                <b-form-textarea
                    id="textarea"
                    v-model="form.content"
                    placeholder="Join the discussion"
                    rows="3"
                    max-rows="6"
                    required
                >
                </b-form-textarea>

                <div class="d-flex flex-row-reverse">
                    <b-button @click="closeModal" class="mt-3 ml-3" type="button" variant="default">
                        <span> Cancel </span>
                    </b-button>
                    <b-button class="mt-3" type="submit" variant="primary">
                        <span v-if="isLoading">Please wait..</span>
                        <span v-else>Save</span>
                    </b-button>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
'use strict';

import axios from 'axios';

export default {
    name: 'CommentEditModal',

    data() {
        return {
            form: { content: null },
            commentId: null,
            isLoading: false,
        };
    },

    methods: {
        saveComment(event) {
            event.preventDefault();

            axios.put('/api/comments/' + this.commentId, this.form).then(() => {
                this.$emit('comment-save', true);
                this.closeModal();
            });
        },

        showModal(content, commentId) {
            this.commentId = commentId;
            this.form.content = content;

            this.$refs.commentEditModal.show();
        },

        closeModal() {
            this.reset();
            this.$refs.commentEditModal.hide();
        },

        reset() {
            this.commentId = null;
            this.form.content = null;
        },
    },
};
</script>
