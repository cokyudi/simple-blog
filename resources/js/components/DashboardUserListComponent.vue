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
                        <button type="button" class="btn btn-primary float-end" @click="userModal('new')">Create New User</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="users && users?.length > 0">
                                <tr v-for="(user,index) in users" :key="index">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        <button @click="userModal('edit', user.id)" type="button" class="btn btn-light me-2">Edit</button>
                                        <button @click="deleteModal(user.id)" type="button" class="btn btn-danger">Delete</button>
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
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <label class="mb-1">Name</label>
                            <input v-model="name" type="text" class="form-control" id="name" placeholder="Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-1">Email</label>
                            <input v-model="email" type="text" class="form-control" id="email" placeholder="Email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-1">Password</label>
                            <input v-model="password" type="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-1">Confirm Password</label>
                            <input v-model="confirmPassword" type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="submitUser">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="liveToast" class="toast text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    User submitted.
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
                    <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                    <button type="button" ref="CloseDelete" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                Are you sure want to delete this user ?
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteUser(this.deleteUserTemp)">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
        <div id="deleteToast" class="toast text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    User Deleted.
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
        this.getUsers();
    },
    data() {
        return {
            errorMessage: [],
            submitError: false,
            isLoading: false,
            users: [],
            modalTitle: '',
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            action:'',
            isSubmitUser: false,
            userId: '',
            deleteUserTemp: ''
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
        getUsers() {
            this.isLoading = true;
            axios.post('get-users')
                .then(response => (
                    console.log(response),
                    this.users = response.data.data,
                    this.isLoading = false
                ))
        },
        userModal(action, id) {
            this.name = '';
            this.email = '';
            this.password = '';
            this.confirmPassword = '';
            this.action = action;
            if (action === 'new') {
                this.modalTitle = 'Create New User';
            } else {
                this.modalTitle = 'Edit User';
            }

            var myModal = new Modal(document.getElementById('userModal'), {
                keyboard: false
            });
            if (id) {
                this.userId = id;
                let formData = new FormData();
                formData.append("id", this.userId);
                axios.post('get-user-by-id', formData)
                    .then(response => {
                        this.name = response.data?.data?.name;
                        this.email = response.data?.data?.email;
                        myModal.show();
                    })
            } else {
                myModal.show();
            }
        },
        validateForm() {
            if (!this.name) {
                this.errorMessage.push("Name cannot be empty");
            }
            if (!this.email) {
                this.errorMessage.push("Email cannot be empty");
            }
            if (!this.password) {
                this.errorMessage.push("Password cannot be empty");
            }
            if (this.password && this.password!==this.confirmPassword) {
                this.errorMessage.push("Confirm password doesn't match Password");
            }
            if (this.errorMessage.length > 0) {
                this.submitError = true;
                return false;
            }
            return true;
        },
        submitUser(e) {
            this.isSubmitUser = true;
            e.preventDefault();
            this.errorMessage = [];
            if (!this.validateForm()) {
                this.isSubmitUser = false;
                return false;
            }
            
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("email", this.email);
            formData.append("password", this.password);
            let url;
            if (this.action==='new') {
                url = '/dashboard/create-user'
            } else {
                url = '/dashboard/edit-user'
                formData.append("id", this.userId);
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
                this.getUsers();
            })
                .catch((err) => {
                existingObj.output = err;
            });
        },
        deleteModal(id) {
            var deleteModal = new Modal(document.getElementById('deleteModal'), {
                keyboard: false
            });
            this.deleteUserTemp = id;
            deleteModal.show();
        },
        deleteUser(id) {
            let existingObj = this;
            let formData = new FormData();
            formData.append("id", id);
            axios.post("/dashboard/delete-user", formData)
                .then((res) => {
                    existingObj.success = res.data.success;
                    this.$refs.CloseDelete.click();
                    this.showDeleteToast();
                    this.getUsers();
                })
                .catch((err) => {
                    existingObj.output = err;
                });
        }

    },
}
</script>