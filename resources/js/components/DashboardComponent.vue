<template>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="position-sticky" style="top: 2rem;">   
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button :class="activeTab === 'createNewPost' ? 'active' : ''" @click="redirectPage('/dashboard/create-new-post', 'createNewPost')" class="nav-link" id="v-pills-home-tab" data-toggle="pill" data-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Create New Post</button>
                        <button :class="activeTab === 'postList' ? 'active' : ''" @click="redirectPage('/dashboard/post-list', 'postList')" class="nav-link" id="v-pills-profile-tab" data-toggle="pill" data-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">My Posts</button>
                        <button v-if="userInfo?.type===1" :class="activeTab === 'userList' ? 'active' : ''" @click="redirectPage('/dashboard/user-list', 'userList')" class="nav-link" id="v-pills-messages-tab" data-toggle="pill" data-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">User list</button>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <router-view></router-view>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';
    
    export default {
    mounted() {
        console.log("Component mounted.");
        this.getLoggedInUser();
        this.$router.push({ path: '/dashboard/create-new-post' });
    },
    data() {
        return {
            activeTab: "createNewPost",
            userInfo: null
        };
    },
    methods: {
        redirectPage(page, tab) {
            this.activeTab = tab;
            this.$router.push({ path: page });
        },
        getLoggedInUser() {
            axios.get('/dashboard/get-loggedin-user')
                .then(response => {
                    this.userInfo = response.data.data
                })
        }
    },
}
</script>