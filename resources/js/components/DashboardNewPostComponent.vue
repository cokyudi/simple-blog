<template>
    <div class="card">
        <h5 class="card-header">Create New Post</h5>
        <div class="card-body">
            <div v-show=submitError class="alert alert-danger" role="alert">
                <li v-for="message in errorMessage">
                    {{ message }}
                </li>
            </div>
            <form>
                <div class="form-group mb-3">
                    <label class="mb-1">Title</label>
                    <input v-model="title" type="text" class="form-control" id="title" placeholder="Title" required>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-1">Content</label>
                    <textarea rows="15" v-model="body" class="form-control" id="title" placeholder="Content" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Image</label>
                    <div class="mb-1">
                        <input  v-on:change="onChangeImage" type="file" ref="fileupload" class="form-control-file">
                    </div>
                </div>
            </form>
            <div class="text-end">
                <button type="button" class="btn btn-primary" @click="createPost" :disabled="isCreatingPost">
                    {{ isCreatingPost ? 'Submitting ...':'Submit'}}
                </button>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="liveToast" class="toast text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Post Submitted.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</template>

<script>
    import { Toast } from 'bootstrap';
    
    export default {
    mounted() {
        console.log("Component mounted.");
    },
    data() {
        return {
            activeTab: "createNewPost",
            title: "",
            body: "",
            image: "",
            errorMessage: [],
            submitError: false,
            isCreatingPost: false
        };
    },
    methods: {
        showToast() {
            var toastLiveExample = document.getElementById("liveToast");
            var toast = new Toast(toastLiveExample);
            toast.show();
        },
        onChangeImage(e) {
            this.image = e.target.files[0];
        },
        validateForm() {
            if (!this.title) {
                this.errorMessage.push("Title cannot be empty");
            }
            if (!this.body) {
                this.errorMessage.push("Content cannot be empty");
            }
            if (!this.image) {
                this.errorMessage.push("Image cannot be empty");
            }
            if (this.image && ["image/jpeg", "image/png"].indexOf(this.image.type) === -1) {
                this.errorMessage.push("File must be JPEG or PNG");
            }
            if (this.errorMessage.length > 0) {
                this.submitError = true;
                return false;
            }
            return true;
        },
        createPost(e) {
            this.isCreatingPost = true;
            e.preventDefault();
            this.errorMessage = [];
            if (!this.validateForm()) {
                this.isCreatingPost = false;
                return false;
            }
            let formData = new FormData();
            formData.append("title", this.title);
            formData.append("body", this.body);
            formData.append("image", this.image);
            let existingObj = this;
            const config = {
                headers: {
                    "content-type": "multipart/form-data"
                }
            };
            axios.post("/post/create_post", formData, config)
                .then((res) => {
                existingObj.success = res.data.success;
                this.title = "";
                this.body = "";
                this.image = "";
                this.$refs.fileupload.value = null;
                this.errorMessage = [];
                this.submitError = false;
                this.isCreatingPost = false;
                this.showToast();
            })
                .catch((err) => {
                existingObj.output = err;
            });
        }
    },
}
</script>