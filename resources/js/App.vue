<template>
  <div>
    <h1 class="text-3xl font-bold underline">
    Hello world!
  </h1>
    <router-link to="index">Index</router-link>
    <router-link to="page">Page</router-link>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>
