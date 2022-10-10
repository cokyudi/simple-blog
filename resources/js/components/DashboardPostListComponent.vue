<template>
    <div v-show=isLoading class="text-center mt-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div v-for="post in posts" class="col">
            <div class="card h-100  cursor-pointer">
                <img v-if="post.post_images" :src="post.post_images.post_image_path" class="card-img-top img-fluid" style="height: 200px;" :alt="post.post_images.post_image_caption">
                <div class="card-body" @click="redirectPage('/dashboard/post-detail/'+post.id)">
                    <div style="height:100px;">
                        <h4 class="card-title text-truncate-4-line">{{ post.title }}</h4>
                    </div>
                    <div class="mt-2">
                        <span class="text-muted">By <strong>{{post.author?.name}}</strong></span>
                    </div>
                    <div class="mt-1">
                        <span class="text-muted">{{calendarDate(post.updated_at)}}</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button @click="redirectPage('/dashboard/edit-post/'+post.id)" class="btn btn-light me-md-2 w-50" type="button">Edit</button>
                        <button @click="deleteModal(post.id)" class="btn btn-danger w-50" type="button">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
                    <button type="button" ref="Close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                Are you sure want to delete this post ?
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deletePost(this.deletePostTemp)">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="liveToast" class="toast text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Post Deleted.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import {Modal,Toast} from 'bootstrap';
    export default {
    mounted() {
        console.log("Component mounted.");
        this.getPosts();
    },
    data() {
        return {
            posts:[],
            isLoading: false,
            deletePostTemp: ''
        };
    },
    methods: {
        showToast() {
            var toastLiveExample = document.getElementById("liveToast");
            var toast = new Toast(toastLiveExample);
            toast.show();
        },
        getPosts() {
            this.isLoading = true;
            axios.get('/post/get_user_posts')
                .then(response => (
                    this.isLoading = false,
                    this.posts = response.data.data
                ))
        },
        calendarDate(date){
            return moment(date).calendar();
        },
        redirectPage(page) {
            this.$router.push({ path: page });
        },
        deleteModal(id) {
            var myModal = new Modal(document.getElementById('deleteModal'), {
                keyboard: false
            });
            this.deletePostTemp = id;
            myModal.show();
        },
        deletePost(id) {
            let existingObj = this;
            let formData = new FormData();
            formData.append("id", id);
            axios.post("/post/delete_post", formData)
                .then((res) => {
                    existingObj.success = res.data.success;
                    this.$refs.Close.click();
                    this.showToast();
                })
                .catch((err) => {
                    existingObj.output = err;
                });
        }
    },
    }
</script>