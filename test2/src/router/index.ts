import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/Home.vue'
import Login from '../components/Login.vue'
import ProfileView from '../views/Profile.vue'
import SettingsView from '../views/Settings.vue'
const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  
  { path: '/profile', name: 'profile', component: ProfileView },
  { path: '/settings', name: 'settings', component: SettingsView }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// 路由守卫
router.beforeEach((to, from, next) => {
  const token = window.sessionStorage.getItem('token')

  // 如果目标路由是主页且没有 token，则跳转到 login 页面
  if (to.name === 'home' && !token) {
    next({ name: 'login' })
  } else {
    next()  // 允许访问
  }
})

export default router
