<template>
    <div id="app">
        <body-layout>
            <router-view></router-view>
        </body-layout>
    </div>
</template>

<script>
'use strict';

import BodyLayout from './components/BodyLayout';

export default {
    name: 'App',
    components: {
        BodyLayout,
    },
};
</script>
