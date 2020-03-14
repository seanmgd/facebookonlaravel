<template>
    <div>
        <div class="w-100 h-64 overflow-hidden">
            <img src="" alt="user background image" class="object-cover w-full">
        </div>
    </div>
</template>

<script>
    export default {
        name: "Show",
        data: () => {
            return {
                user: null,
                userLoading: true,
                postLoading: true,
            }
        },
        mounted() {
            axios.get('/api/users/' + this.$route.params.userId)
                .then(res => {
                    this.user = res.data;
                })
                .catch(error => {
                    console.log('Unable to fetch the user from the server')
                })
                .finally(() => {
                    this.userLoading = false
                });

            axios.get('api/posts' + this.$routes.params.userId)
                .then(res => {
                    this.posts = res.data;
                })
                .catch(error => {
                    console.log('Unable to fetch posts');
                })
                .finally(() => {
                    this.postLoading = false
                });
        }
    }
</script>

<style scoped>

</style>
