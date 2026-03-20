import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import LoginPage from '@/pages/auth/LoginPage.vue';
import DashboardPage from '@/pages/dashboard/DashboardPage.vue';
import ClientsListPage from '@/pages/clients/ClientsListPage.vue';
import ClientFormPage from '@/pages/clients/ClientFormPage.vue';
import ProductsListPage from '@/pages/products/ProductsListPage.vue';
import ProductFormPage from '@/pages/products/ProductFormPage.vue';
import ProposalsListPage from '@/pages/proposals/ProposalsListPage.vue';
import ProposalFormPage from '@/pages/proposals/ProposalFormPage.vue';
import UsersPermissionsPage from '@/pages/users/UsersPermissionsPage.vue';
import UserFormPage from '@/pages/users/UserFormPage.vue';
import ExcelImportPage from '@/pages/imports/ExcelImportPage.vue';
import AuditLogsPage from '@/pages/audits/AuditLogsPage.vue';

const routes: RouteRecordRaw[] = [
  { path: '/login', name: 'login', component: LoginPage, meta: { guestOnly: true } },
  { path: '/', name: 'dashboard', component: DashboardPage, meta: { requiresAuth: true } },
  { path: '/clients', name: 'clients', component: ClientsListPage, meta: { requiresAuth: true } },
  { path: '/clients/new', name: 'clients-new', component: ClientFormPage, meta: { requiresAuth: true } },
  { path: '/products', name: 'products', component: ProductsListPage, meta: { requiresAuth: true } },
  { path: '/products/new', name: 'products-new', component: ProductFormPage, meta: { requiresAuth: true } },
  { path: '/proposals', name: 'proposals', component: ProposalsListPage, meta: { requiresAuth: true } },
  { path: '/proposals/new', name: 'proposals-new', component: ProposalFormPage, meta: { requiresAuth: true } },
  { path: '/users', name: 'users', component: UsersPermissionsPage, meta: { requiresAuth: true } },
  { path: '/users/new', name: 'users-new', component: UserFormPage, meta: { requiresAuth: true } },
  { path: '/imports', name: 'imports', component: ExcelImportPage, meta: { requiresAuth: true } },
  { path: '/audits', name: 'audits', component: AuditLogsPage, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login' };
  }

  if (to.meta.guestOnly && auth.isAuthenticated) {
    return { name: 'dashboard' };
  }

  return true;
});

export default router;
