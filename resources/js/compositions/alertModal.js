import {computed, reactive, readonly} from 'vue'

const state = reactive({
  isOpen: false,
})

export default function alertModal() {
  function open() {
    state.isOpen = true
  }

  function close() {
    state.isOpen = false
  }

  const getIsOpen = computed(() => state.isOpen)

  return {
    state: readonly(state.isOpen),
    open,
    close,
    getIsOpen,
  }
}
