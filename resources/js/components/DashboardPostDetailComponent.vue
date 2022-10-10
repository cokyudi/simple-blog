<template>
    <div class="bg-white p-5">
        <div v-show=isLoading class="text-center mt-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-show="!isLoading">
            <div class="mb-3">
                <h1><strong>{{ post.title }}</strong></h1>
            </div>
            <div class="mb-1">
                <span class="text-muted">By <strong>{{post.author?.name}}</strong></span>
            </div>
            <div class="mb-1">
                <span class="text-muted">{{ getDay(post.updated_at) }}, {{ getDate(post.updated_at) }}</span>
            </div>
            <div> 
                <img v-if="post?.post_images" :src="post?.post_images?.post_image_path" class="img-fluid" :alt="post?.post_images?.post_image_caption">
            </div>
            <div class="my-5">
                <p class="lead">{{post.body}}</p>
            </div>
        </div>
    </div>

</template>

<script>
    import moment from 'moment';
    export default {
    mounted() {
        console.log("Component mounted.");
        this.getPost();
    },
    data() {
        return {
            post:[],
            isLoading:false,
        };
    },
    methods: {
        getPost() {
            this.isLoading = true;
            axios.get('/post/get_post_by_id/'+this.$route.params.id)
                .then(response => (
                    this.isLoading = false,
                    this.post = response.data.data[0]
                ))
        },
        getDay(date) {
            return moment(date).format('dddd')
        },
        getDate(date) {
            return moment(date).format('D MMMM YYYY')
        }
    },
    }
</script>