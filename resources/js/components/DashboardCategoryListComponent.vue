<template>
    <div v-show=isLoading class="text-center mt-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div v-show=!isLoading class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 clearfix">
                        <button type="button" class="btn btn-primary float-end" @click="categoryModal('new')">Create New Category</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category Name</th>
                                </tr>
                            </thead>
                            <tbody v-if="categories && categories?.length > 0">
                                <tr v-for="(category,index) in categories" :key="index">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ category.category_name }}</td>
                                    <td>
                                        <button @click="categoryModal('edit', category.id)" type="button" class="btn btn-light me-2">Edit</button>
                                        <button @click="deleteModal(category.id)" type="button" class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td align="center" colspan="3">No record found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{modalTitle}}</h5>
                    <button type="button" ref="Close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-show=submitError class="alert alert-danger" role="alert">
                        <li v-for="message in errorMessage">
                            {{ message }}
                        </li>
                    </div>
                    <form>
                        <div class="form-group mb-3">
                            <label class="mb-1">Category Name</label>
                            <input v-model="category_name" type="text" class="form-control" id="category_name" placeholder="Category Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="submitCategory">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="liveToast" class="toast text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Category submitted.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                    <button type="button" ref="CloseDelete" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                Are you sure want to delete this category ?
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteUser(this.deleteCategoryTemp)">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="deleteToast" class="toast text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Category Deleted.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { Modal, Toast } from 'bootstrap';
    
    export default {
    mounted() {
        console.log("Component mounted.");
        this.getCategories();
    },
    data() {
        return {
            errorMessage: [],
            submitError: false,
            isLoading: false,
            categories: [],
            modalTitle: '',
            category_name: '',
            action:'',
            isSubmitCategory: false,
            categoryId: '',
            deleteCategoryTemp: ''
        };
    },
    methods: {
        showToast() {
            var toastLiveExample = document.getElementById("liveToast");
            var toast = new Toast(toastLiveExample);
            toast.show();
        },
        showDeleteToast() {
            var toastLiveExample = document.getElementById("deleteToast");
            var toast = new Toast(toastLiveExample);
            toast.show();
        },
        getCategories() {
            this.isLoading = true;
            axios.post('get-categories')
                .then(response => (
                    this.categories = response.data.data,
                    this.isLoading = false
                ))
        },
        categoryModal(action, id) {
            this.category_name = '';
            this.action = action;
            if (action === 'new') {
                this.modalTitle = 'Create New Category';
            } else {
                this.modalTitle = 'Edit Category';
            }

            var myModal = new Modal(document.getElementById('categoryModal'), {
                keyboard: false
            });
            if (id) {
                this.categoryId = id;
                let formData = new FormData();
                formData.append("id", this.categoryId);
                axios.post('get-category-by-id', formData)
                    .then(response => {
                        this.category_name = response.data?.data?.category_name;
                        myModal.show();
                    })
            } else {
                myModal.show();
            }
        },
        validateForm() {
            if (!this.category_name) {
                this.errorMessage.push("Category Name cannot be empty");
            }
            if (this.errorMessage.length > 0) {
                this.submitError = true;
                return false;
            }
            return true;
        },
        submitCategory(e) {
            this.isSubmitCategory = true;
            e.preventDefault();
            this.errorMessage = [];
            if (!this.validateForm()) {
                this.isSubmitCategory = false;
                return false;
            }
            
            let formData = new FormData();
            formData.append("category_name", this.category_name);
            let url;
            if (this.action==='new') {
                url = '/dashboard/create-category'
            } else {
                url = '/dashboard/edit-category'
                formData.append("id", this.categoryId);
            }
            let existingObj = this;
            axios.post(url, formData)
                .then((res) => {
                existingObj.success = res.data.success;
                this.errorMessage = [];
                this.submitError = false;
                this.isSubmitUser = false;
                this.showToast();
                this.$refs.Close.click();
                this.getCategories();
            })
                .catch((err) => {
                existingObj.output = err;
            });
        },
        deleteModal(id) {
            var deleteModal = new Modal(document.getElementById('deleteModal'), {
                keyboard: false
            });
            this.deleteCategoryTemp = id;
            deleteModal.show();
        },
        deleteCategory(id) {
            let existingObj = this;
            let formData = new FormData();
            formData.append("id", id);
            axios.post("/dashboard/delete-category", formData)
                .then((res) => {
                    existingObj.success = res.data.success;
                    this.$refs.CloseDelete.click();
                    this.showDeleteToast();
                    this.getCategories();
                })
                .catch((err) => {
                    existingObj.output = err;
                });
        }

    },
}
</script>