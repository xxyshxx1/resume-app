<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-5xl mx-auto p-6 py-12">
      <!-- Header -->
      <header class="text-center mb-16">
        <h1 class="text-5xl font-bold text-gray-900 mb-3">My Resume</h1>
        <p class="text-xl text-gray-600">Full Stack Developer</p>
        <div class="w-24 h-1 bg-blue-500 mx-auto mt-4"></div>
      </header>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        <p class="text-gray-600 mt-4">Loading...</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <p class="text-red-600">{{ error }}</p>
      </div>

      <!-- Resume Sections -->
      <div v-else class="space-y-8">
        <section 
          v-for="section in sections" 
          :key="section.id"
          class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow"
        >
          <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-blue-500 pb-3">
            {{ section.title }}
          </h2>
          
          <!-- About -->
          <div v-if="section.type === 'about'" class="text-gray-700 text-lg leading-relaxed">
            {{ section.content }}
          </div>

          <!-- Experience -->
          <div v-if="section.type === 'experience'" class="space-y-6">
            <div 
              v-for="(exp, index) in section.data" 
              :key="index"
              class="border-l-4 border-blue-500 pl-6"
            >
              <h3 class="font-bold text-xl text-gray-900">{{ exp.position }}</h3>
              <p class="text-gray-600 font-medium">{{ exp.company }}</p>
              <p class="text-sm text-gray-500 mb-3">{{ exp.period }}</p>
              <p class="text-gray-700 mb-3">{{ exp.description }}</p>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="tech in exp.technologies" 
                  :key="tech"
                  class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
          </div>

          <!-- Education -->
          <div v-if="section.type === 'education'" class="space-y-6">
            <div 
              v-for="(edu, index) in section.data" 
              :key="index"
              class="border-l-4 border-green-500 pl-6"
            >
              <h3 class="font-bold text-xl text-gray-900">{{ edu.degree }}</h3>
              <p class="text-gray-600 font-medium">{{ edu.institution }}</p>
              <p class="text-sm text-gray-500 mb-3">{{ edu.period }}</p>
              <p class="text-gray-700">{{ edu.description }}</p>
            </div>
          </div>

          <!-- Skills -->
          <div v-if="section.type === 'skills'" class="space-y-5">
            <div v-for="(skills, category) in section.data" :key="category">
              <h3 class="font-semibold text-lg capitalize text-gray-800 mb-3">{{ category }}</h3>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="skill in skills" 
                  :key="skill"
                  class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm"
                >
                  {{ skill }}
                </span>
              </div>
            </div>
          </div>

          <!-- Projects -->
          <div v-if="section.type === 'projects'" class="space-y-6">
            <div 
              v-for="(project, index) in section.data" 
              :key="index"
              class="border-l-4 border-purple-500 pl-6"
            >
              <h3 class="font-bold text-xl text-gray-900">{{ project.name }}</h3>
              <p class="text-gray-700 mb-3">{{ project.description }}</p>
              <div class="flex flex-wrap gap-2 mb-3">
                <span 
                  v-for="tech in project.technologies" 
                  :key="tech"
                  class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm"
                >
                  {{ tech }}
                </span>
              </div>
              <a 
                v-if="project.link" 
                :href="project.link" 
                class="text-blue-600 hover:underline font-medium"
              >
                View Project →
              </a>
            </div>
          </div>
        </section>
      </div>

      <!-- Footer -->
      <footer class="text-center mt-16 text-gray-600">
        <p>No resume yet, please click "Edit Resume" to add</p>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const sections = ref([])
const loading = ref(true)
const error = ref(null)

const fetchResume = async () => {
  try {
    const response = await fetch('/api/resume')
    if (!response.ok) throw new Error('Failed to fetch')
    sections.value = await response.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchResume)
</script>
