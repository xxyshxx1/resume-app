<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit My Resume</h1>
        <router-link 
          to="/" 
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
        >
          View Resume
        </router-link>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        <p class="text-gray-600 mt-4">Loading...</p>
      </div>

      <!-- No Sections - Initialize -->
      <div v-else-if="sections.length === 0" class="bg-white rounded-xl shadow-lg p-8 text-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Welcome! Let's set up your resume</h2>
        <p class="text-gray-600 mb-6">Click the button below to create default resume sections.</p>
        <button 
          @click="initializeSections"
          :disabled="initializing"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
        >
          {{ initializing ? 'Creating...' : 'Create My Resume' }}
        </button>
      </div>

      <!-- Edit Sections -->
      <div v-else class="space-y-6">
        <div 
          v-for="section in sections" 
          :key="section.id"
          class="bg-white rounded-xl shadow-lg p-6"
        >
          <!-- Section Header -->
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">{{ section.title }}</h2>
            <span class="text-sm bg-gray-100 px-3 py-1 rounded-full text-gray-600">
              {{ section.type }}
            </span>
          </div>

          <!-- About Section -->
          <div v-if="section.type === 'about'">
            <textarea
              v-model="section.content"
              rows="4"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Write about yourself..."
            ></textarea>
          </div>

          <!-- Experience Section -->
          <div v-else-if="section.type === 'experience'" class="space-y-4">
            <div 
              v-for="(exp, index) in section.data" 
              :key="index"
              class="border border-gray-200 rounded-lg p-4 relative"
            >
              <button 
                @click="removeItem(section, index)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
              <div class="grid grid-cols-2 gap-4 mb-3">
                <input v-model="exp.position" placeholder="Position" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input v-model="exp.company" placeholder="Company" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input v-model="exp.period" placeholder="Period (e.g., 2020 - Present)" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input 
                  v-model="exp.technologiesInput" 
                  @blur="parseTechnologies(exp)"
                  placeholder="Technologies (comma separated)" 
                  class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                />
              </div>
              <textarea 
                v-model="exp.description" 
                placeholder="Description" 
                rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              ></textarea>
            </div>
            <button @click="addExperience(section)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">+ Add Experience</button>
          </div>

          <!-- Education Section -->
          <div v-else-if="section.type === 'education'" class="space-y-4">
            <div 
              v-for="(edu, index) in section.data" 
              :key="index"
              class="border border-gray-200 rounded-lg p-4 relative"
            >
              <button 
                @click="removeItem(section, index)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
              <div class="grid grid-cols-2 gap-4 mb-3">
                <input v-model="edu.degree" placeholder="Degree" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input v-model="edu.institution" placeholder="Institution" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input v-model="edu.period" placeholder="Period (e.g., 2016 - 2020)" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
              </div>
              <textarea 
                v-model="edu.description" 
                placeholder="Description" 
                rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              ></textarea>
            </div>
            <button @click="addEducation(section)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">+ Add Education</button>
          </div>

          <!-- Skills Section -->
          <div v-else-if="section.type === 'skills'" class="space-y-4">
            <div v-for="(skills, category) in section.data" :key="category" class="border border-gray-200 rounded-lg p-4">
              <label class="block text-sm font-medium text-gray-700 mb-2 capitalize">{{ category }}</label>
              <input 
                :value="Array.isArray(skills) ? skills.join(', ') : ''"
                @input="updateSkills(section, category, $event.target.value)"
                placeholder="Enter skills separated by comma"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              />
            </div>
            <div class="flex gap-2">
              <input 
                v-model="newSkillCategory" 
                placeholder="New category name" 
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none flex-1"
              />
              <button @click="addSkillCategory(section)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">+ Add Category</button>
            </div>
          </div>

          <!-- Projects Section -->
          <div v-else-if="section.type === 'projects'" class="space-y-4">
            <div 
              v-for="(project, index) in section.data" 
              :key="index"
              class="border border-gray-200 rounded-lg p-4 relative"
            >
              <button 
                @click="removeItem(section, index)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
              <div class="grid grid-cols-2 gap-4 mb-3">
                <input v-model="project.name" placeholder="Project Name" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input v-model="project.link" placeholder="Project Link (optional)" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                <input 
                  v-model="project.technologiesInput" 
                  @blur="parseTechnologies(project)"
                  placeholder="Technologies (comma separated)" 
                  class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none col-span-2" 
                />
              </div>
              <textarea 
                v-model="project.description" 
                placeholder="Description" 
                rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              ></textarea>
            </div>
            <button @click="addProject(section)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">+ Add Project</button>
          </div>

          <!-- Save Button -->
          <div class="mt-4 flex justify-end">
            <button 
              @click="saveSection(section)"
              :disabled="saving === section.id"
              class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-50"
            >
              {{ saving === section.id ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Success/Error Messages -->
      <div 
        v-if="message" 
        :class="messageType === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'"
        class="fixed bottom-4 right-4 px-6 py-3 rounded-lg border shadow-lg"
      >
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const sections = ref([])
const loading = ref(true)
const saving = ref(null)
const initializing = ref(false)
const message = ref('')
const messageType = ref('success')
const newSkillCategory = ref('')

const getCookie = (name) => {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) {
    return decodeURIComponent(parts.pop().split(';').shift())
  }
  return ''
}

const showMessage = (msg, type = 'success') => {
  message.value = msg
  messageType.value = type
  setTimeout(() => {
    message.value = ''
  }, 3000)
}

const fetchSections = async () => {
  try {
    const response = await fetch('/api/resume', {
      credentials: 'same-origin',
    })
    if (response.ok) {
      const data = await response.json()
      // Prepare data for editing
      sections.value = data.map(section => {
        if (section.type === 'experience' || section.type === 'projects') {
          section.data = (section.data || []).map(item => ({
            ...item,
            technologiesInput: (item.technologies || []).join(', ')
          }))
        }
        return section
      })
    }
  } catch (err) {
    showMessage('Failed to load sections', 'error')
  } finally {
    loading.value = false
  }
}

const initializeSections = async () => {
  initializing.value = true
  try {
    const response = await fetch('/api/resume/initialize', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
      },
      credentials: 'same-origin',
    })
    if (response.ok) {
      await fetchSections()
      showMessage('Resume sections created!')
    }
  } catch (err) {
    showMessage('Failed to create sections', 'error')
  } finally {
    initializing.value = false
  }
}

const saveSection = async (section) => {
  saving.value = section.id
  
  // Prepare data for saving
  const dataToSave = { ...section }
  if (section.type === 'experience' || section.type === 'projects') {
    dataToSave.data = section.data.map(item => {
      const { technologiesInput, ...rest } = item
      return {
        ...rest,
        technologies: technologiesInput ? technologiesInput.split(',').map(t => t.trim()).filter(t => t) : []
      }
    })
  }
  
  try {
    const response = await fetch(`/api/resume/${section.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
      },
      credentials: 'same-origin',
      body: JSON.stringify(dataToSave),
    })
    if (response.ok) {
      showMessage('Section saved successfully!')
    } else {
      throw new Error('Save failed')
    }
  } catch (err) {
    showMessage('Failed to save section', 'error')
  } finally {
    saving.value = null
  }
}

const parseTechnologies = (item) => {
  if (item.technologiesInput) {
    item.technologies = item.technologiesInput.split(',').map(t => t.trim()).filter(t => t)
  }
}

const addExperience = (section) => {
  if (!section.data) section.data = []
  section.data.push({
    position: '',
    company: '',
    period: '',
    description: '',
    technologies: [],
    technologiesInput: ''
  })
}

const addEducation = (section) => {
  if (!section.data) section.data = []
  section.data.push({
    degree: '',
    institution: '',
    period: '',
    description: ''
  })
}

const addProject = (section) => {
  if (!section.data) section.data = []
  section.data.push({
    name: '',
    description: '',
    link: '',
    technologies: [],
    technologiesInput: ''
  })
}

const addSkillCategory = (section) => {
  if (newSkillCategory.value.trim()) {
    if (!section.data) section.data = {}
    section.data[newSkillCategory.value.toLowerCase().trim()] = []
    newSkillCategory.value = ''
  }
}

const updateSkills = (section, category, value) => {
  section.data[category] = value.split(',').map(s => s.trim()).filter(s => s)
}

const removeItem = (section, index) => {
  section.data.splice(index, 1)
}

onMounted(fetchSections)
</script>

