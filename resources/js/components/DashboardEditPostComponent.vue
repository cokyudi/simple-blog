<template>
    <div v-show=isLoading class="text-center mt-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="card" v-show=!isLoading>
        <h5 class="card-header">Edit Post</h5>
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
                    <label class="mb-1">Category</label>
                    <select class="form-select" v-model="category">
                        <option value="" :selected="!category" disabled>-- Select Category --</option>
                        <option v-for="item in categories" :value="item.id">{{ item.category_name }}</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-1">Content</label>
                    <ckeditor 
                        :editor="editor" 
                        v-model="body" 
                        :config="editorConfig"
                    />
                    <!-- <textarea rows="15" v-model="body" class="form-control" id="title" placeholder="Content" required></textarea> -->
                </div>
                <div class="form-group mb-3">
                    <label>Change Image</label>
                    <div class="mb-1">
                        <input  v-on:change="onChangeImage" type="file" ref="fileupload" class="form-control-file">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Previous Image Preview</label>
                    <div class="mb-1">
                        <img :src="post?.post_images?.post_image_path" class="img-fluid" :alt="post?.post_images[0]?.post_image_caption">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-1">Slug</label>
                    <input v-model="slug" type="text" class="form-control" id="slug" placeholder="Slug" required>
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
    import { 
        ClassicEditor, 
        Bold, 
        Essentials, 
        Italic, 
        Mention, 
        Paragraph, 
        Undo, 
        Heading, 
        List,
        FontFamily,
        FontSize,
        FontColor,
        FontBackgroundColor,
        Link,
        Alignment,
        Table,
        TableToolbar,
        TableCellProperties,
        TableProperties,
    } from 'ckeditor5';
    import { Ckeditor } from '@ckeditor/ckeditor5-vue';

    import 'ckeditor5/ckeditor5.css';
    
    export default {
    components: {
        Ckeditor
    },
    mounted() {
        console.log("Component mounted.");
        this.getPost();
        this.getCategories();
    },
    data() {
        return {
            activeTab: "createNewPost",
            title: "",
            body: "",
            image: "",
            category:"",
            slug:"",
            errorMessage: [],
            submitError: false,
            isCreatingPost: false,
            isLoading: false,
            isLoadingCategories: false,
            categories: [],
            editor: ClassicEditor,
            editorConfig: {
                plugins: [ 
                    Bold, 
                    Essentials, 
                    Italic, 
                    Mention, 
                    Paragraph, 
                    Undo,
                    Heading,
                    List,
                    FontFamily,
                    FontSize,
                    FontColor,
                    FontBackgroundColor,
                    Link,
                    Alignment,
                    Table,
                    TableToolbar,
                    TableCellProperties,
                    TableProperties,
                ],
                toolbar: [
                    'undo', 'redo',
                    '|',
                    'heading',
                    '|',
                    'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                    '|',
                    'link', 'uploadImage', 'blockQuote', 'codeBlock',
                    '|',
                    'alignment', 'insertTable',
                    '|',
                    'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                ],
                table: {
                    contentToolbar: [ 
                        'tableColumn', 'tableRow', 'mergeTableCells',
                        'tableProperties', 'tableCellProperties'
                    ]
                }
            }
        };
    },
    methods: {
        showToast() {
            var toastLiveExample = document.getElementById("liveToast");
            var toast = new Toast(toastLiveExample);
            toast.show();
        },
        getCategories() {
            this.isLoadingCategories = true;
            axios.post('/dashboard/get-categories')
                .then(response => (
                    this.categories = response.data.data,
                    this.isLoadingCategories = false
                ))
        },
        getPost() {
            this.isLoading = true;
            axios.get('/post/get_post_by_id/'+this.$route.params.id)
                .then(response => (
                    this.post = response.data.data[0],
                    this.title = this.post.title,
                    this.body = this.post.body,
                    this.category = this.post.category_id,
                    this.slug = this.post.slug,
                    this.isLoading = false
                ))
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
            if (!this.slug) {
                this.errorMessage.push("Slug cannot be empty");
            }
            if (!this.category) {
                this.errorMessage.push("Category cannot be empty");
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
            formData.append("slug", this.slug);
            formData.append("id", this.post.id);
            formData.append("category_id", this.category);
            formData.append("previousImage", this.post.post_images[0]?.id)
            let existingObj = this;
            const config = {
                headers: {
                    "content-type": "multipart/form-data"
                }
            };
            axios.post("/post/edit_post", formData, config)
                .then((res) => {
                existingObj.success = res.data.success;
                this.errorMessage = [];
                this.submitError = false;
                this.isCreatingPost = false;
                this.showToast();
                setTimeout(() => this.$router.push({ path: '/dashboard/post-list' }), 1000);
            })
                .catch((err) => {
                existingObj.output = err;
            });
        }
    },
}
</script>