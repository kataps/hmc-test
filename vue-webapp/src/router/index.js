import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '@/store';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import(/* webpackChunkName: "HomeView" */ '../views/HomeView.vue'),
    },
    {
        path: '/login',
        name: 'login',
        component: () => import(/* webpackChunkName: "LoginView" */ '../views/LoginView.vue'),
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import(/* webpackChunkName: "RegisterView" */ '../views/RegisterView.vue'),
    },
    {
        path: '/posts/create',
        name: 'posts.create',
        component: () => import(/* webpackChunkName: "CreatePost" */ '../views/CreatePost.vue'),
        meta: {
            requires_auth: true,
        },
    },
    {
        path: '/posts/:id',
        name: 'posts.details',
        component: () => import(/* webpackChunkName: "PostDetails" */ '../views/PostDetails.vue'),
        meta: {
            requires_auth: true,
        },
    },
    {
        path: '/posts/:id/edit',
        name: 'posts.edit',
        component: () => import(/* webpackChunkName: "PostEdit" */ '../views/PostEdit.vue'),
        meta: {
            requires_auth: true,
        },
    },
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requires_auth) {
        if (store.getters['auth/isAuthenticated']) {
            next();
        } else {
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
