import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || '')

  // Initialize axios header if token exists
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  function setUser(u) {
    user.value = u
  }
  
  function setToken(t) {
    token.value = t
    if (t) {
      localStorage.setItem('token', t)
      axios.defaults.headers.common['Authorization'] = `Bearer ${t}`
    } else {
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    }
  }
  
  async function fetchUser() {
    try {
      if (token.value && !user.value) {
        const response = await axios.get('/api/auth/user')
        user.value = response.data.user
      }
    } catch (error) {
      console.error('Error fetching user:', error)
      // If there's an error fetching user, clear the token
      logout()
    }
  }
  
  function logout() {
    user.value = null
    setToken('')
  }

  return { user, token, setUser, setToken, fetchUser, logout }
}) 