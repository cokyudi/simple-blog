<template>
    <div class="container">
        <div class="p-4 p-md-5 mb-4 rounded text-bg-dark" style="background-image: url('https://picsum.photos/1920/1080?grayscale&blur');">
            <div class="col-md-6 px-0">
                <h1 class="display-4 fst-italic">YUDI DHARMA PUTRA's BLOG</h1>
                <p class="lead my-3">This is my simple blog project, I hope can continue developing and improving this project more in the future.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 mb-3 bg-white rounded">
                        <h4 class="fst-italic">About</h4>
                        <p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
                    </div>

                    <div class="p-4 bg-white">
                        <h4 class="fst-italic">Category</h4>
                        <ol class="list-unstyled mb-0">
                            <li><a href="#">Tech</a></li>
                            <li><a href="#">Food</a></li>
                            <li><a href="#">Sport</a></li>
                            <li><a href="#">World</a></li>
                            <li><a href="#">Social</a></li>
                            <li><a href="#">Education</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div v-show=isLoading class="text-center mt-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div v-show=!isLoading class="row row-cols-1 row-cols-md-3 g-4">
                    <div v-for="post in posts" class="col">
                        <div class="card h-100  cursor-pointer">
                            <img v-if="post?.post_images" :src="post?.post_images.post_image_path" class="card-img-top img-fluid" style="height: 200px;" :alt="post.post_images.post_image_caption">
                            <div class="card-body" @click="redirectPage(post.slug!==''?'/detail/'+post.slug:'/detail/'+post.id)">
                                <div style="height:100px;">
                                    <h4 class="card-title text-truncate-4-line">{{ post?.title }}</h4>
                                </div>
                                <div class="mt-2">
                                    <span class="text-muted">By <strong>{{post?.author?.name}}</strong></span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-muted">{{calendarDate(post?.updated_at)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show=!isLoading class="float-start mt-3">
                    <span>Page {{currentPage}} of {{lastPage}}</span>
                </div>
                <div v-show=!isLoading class="mt-3 float-end">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li @click="changePage(previousPage)" v-if="previousPage" class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li @click="changePage(nextPage)" v-if="nextPage" class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
    mounted() {
        console.log("Component mounted.");
        this.url = '/post/get_all'
        this.getAllPosts();
    },
    data() {
        return {
            posts:[],
            isLoading: false,
            deletePostTemp: '',
            nextPage: null,
            previousPage: null,
            currentPage: null,
            lastPage: null,
            url: ''
        };
    },
    methods: {
        getAllPosts() {
            this.isLoading = true;
            axios.get(this.url)
                .then(response => (
                    this.isLoading = false,
                    this.posts = response.data.data.data,
                    this.nextPage = response.data.data.next_page_url,
                    this.previousPage = response.data.data.prev_page_url,
                    this.currentPage = response.data.data.current_page,
                    this.lastPage = response.data.data.last_page
                ))
        },
        changePage(url) {
            this.url = url;
            this.getAllPosts();
        },
        calendarDate(date){
            return moment(date).calendar();
        },
        redirectPage(page) {
            window.location.href=page;
        },
    },
    }
</script>