import api from '@/http/api.js'

/**
 * @returns {Promise<*>}
 * @throws {Object}
 */
export const userAuthentication = async() => {
  try {
    const response = await api.get('config')

    return response.data
  } catch (e) {
    throw e.response.data
  }
}