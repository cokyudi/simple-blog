import { createRouter, createWebHistory } from "vue-router";
import DashboardNewPostComponent from '../components/DashboardNewPostComponent.vue';
import DashboardPostListComponent from '../components/DashboardPostListComponent.vue';
import DashboardPostDetailComponent from '../components/DashboardPostDetailComponent.vue';
import DashboardEditPostComponent from '../components/DashboardEditPostComponent.vue';
import DashboardUserListComponent from '../components/DashboardUserListComponent.vue';
import DashboardCategoryListComponent from '../components/DashboardCategoryListComponent.vue';

const routes = [
    {
        path:'/dashboard/create-new-post',
        name:'create-new-post',
        component:DashboardNewPostComponent
    },
    {
        path:'/dashboard/post-list',
        name:'post-list',
        component:DashboardPostListComponent
    },
    {
        path:'/dashboard/user-list',
        name:'user-list',
        component:DashboardUserListComponent
    },
    {
        path:'/dashboard/post-detail/:id',
        name:'post-detail',
        component:DashboardPostDetailComponent
    },
    {
        path:'/dashboard/edit-post/:id',
        name:'edit-post',
        component:DashboardEditPostComponent
    },
    {
        path:'/dashboard/category-list',
        name:'category-list',
        component:DashboardCategoryListComponent
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;