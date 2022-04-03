import axios from 'axios'
import Swal from 'sweetalert2'

const api = axios.create({
  baseURL: '/api/',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
})

api.interceptors.response.use((response) => {
  return response
}, (error) => {


  // 401 Unauthorized
  if (error?.response?.status === 401) {
    Swal.fire({title: 'Unauthorized', icon: 'error'})
      .then(() => window.location = '/login')
  }

  // 403 Forbidden
  if (error?.response?.status === 403) {
    Swal.fire({title: 'Forbidden', icon: 'error'})
      .then(() => window.location = '/login')
  }

  return Promise.reject(error)
})

export default api
