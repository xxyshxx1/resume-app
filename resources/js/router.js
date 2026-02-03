import { createRouter, createWebHistory } from 'vue-router'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import Resume from './components/Resume.vue'
import ResumeEdit from './components/ResumeEdit.vue'

const routes = [
  {
    path: '/',
    name: 'resume',
    component: Resume,
    meta: { requiresAuth: true }
  },
  {
    path: '/edit',
    name: 'resume-edit',
    component: ResumeEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { guest: true }
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guard to check authentication
router.beforeEach(async (to, from, next) => {
  const isAuthenticated = await checkAuth()

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else if (to.meta.guest && isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

// Check if user is authenticated
async function checkAuth() {
  try {
    const response = await fetch('/api/user', {
      credentials: 'same-origin',
    })
    return response.ok
  } catch {
    return false
  }
}

export default router
