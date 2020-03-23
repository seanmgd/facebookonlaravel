<template>
    <div>
        <img
            src="https://images.unsplash.com/photo-1584565169019-f1f4ca64a3e7?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1036&q=80"
            alt="user background image"
            ref="userImage"
            class="object-cover w-full">
    </div>
</template>

<script>
    import Dropzone from 'dropzone';

    export default {
        name: "UploadableImage",

        props: [
            'imageWidth',
            'imageHeight',
            'location',
        ],

        data: () => {
            return {
                dropzone: null,
            }
        },

        mounted() {
            this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
        },

        computed: {
            settings() {
                return {
                    paramName: 'image',
                    url: '/api/user-images',
                    acceptedFiles: 'image/*',
                    params: {
                        'width': this.imageWidth,
                        'height': this.imageHeight,
                        'location': this.location,
                    },
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                    },
                    success:(e, res) => {
                        alert('uploaded!');
                    }
                };
            }
        }
    }
</script>

<style scoped>

</style>
