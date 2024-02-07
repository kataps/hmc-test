<template>
    <div>
        <nav-bar ref="navBar" />
        <div class="container d-flex flex-row-reverse p-3" v-if="isAuthenticated">
            <h5>Welcome {{ loggedUser.name }}!</h5>
        </div>
        <div class="pt-3">
            <slot></slot>
        </div>
    </div>
</template>

<script>
'use strict';
import { mapState } from 'vuex';
import NavBar from '@/components/NavBar';

export default {
    name: 'BodyLayout',

    components: {
        NavBar,
    },

    computed: {
        ...mapState({
            isAuthenticated: (state) => state.auth.isAuthenticated,
            loggedUser: (state) => state.auth.user,
        }),
    },
};
</script>
