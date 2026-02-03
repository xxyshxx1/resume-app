<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Navigation Bar -->
    <nav v-if="user" class="bg-white shadow-sm">
      <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
        <router-link to="/" class="text-xl font-bold text-gray-900">Resume App</router-link>
        <div class="flex items-center gap-6">
          <router-link to="/" class="text-gray-600 hover:text-gray-900">View Resume</router-link>
          <router-link to="/edit" class="text-gray-600 hover:text-gray-900">Edit Resume</router-link>
          <span class="text-gray-400">|</span>
          <span class="text-gray-600">{{ user.name }}</span>
          <button
            @click="handleLogout"
            class="text-sm text-red-600 hover:text-red-800 font-medium"
          >
            Logout
          </button>
        </div>
      </div>
    </nav>

    <!-- Router View -->
    <router-view @login="handleLogin" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref(null)

const fetchUser = async () => {
  try {
    const response = await fetch('/api/user', {
      credentials: 'same-origin',
    })
    if (response.ok) {
      const data = await response.json()
      user.value = data.user
    }
  } catch {
    user.value = null
  }
}

const handleLogin = (userData) => {
  user.value = userData
}

const handleLogout = async () => {
  try {
    await fetch('/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
      },
      credentials: 'same-origin',
    })
    user.value = null
    router.push('/login')
  } catch (err) {
    console.error('Logout failed:', err)
  }
}

const getCookie = (name) => {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) {
    return decodeURIComponent(parts.pop().split(';').shift())
  }
  return ''
}

onMounted(fetchUser)
</script>

