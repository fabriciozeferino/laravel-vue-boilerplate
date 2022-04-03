<template>
  <TransitionRoot
    :show="isOpen"
    :v-if="isOpen"
    name="fade"
  >
    <Dialog
      as="div"
      class="fixed z-10 inset-0 overflow-y-auto"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        @click.self="close"
      >
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
          @click.self="close"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>
        <!---->
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span
          aria-hidden="true"
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
        >&#8203;</span>
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to="opacity-100 translate-y-0 sm:scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 translate-y-0 sm:scale-100"
          leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <ExclamationIcon
                  aria-hidden="true"
                  class="h-6 w-6 text-red-600"
                />
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <DialogTitle
                  as="h3"
                  class="text-lg leading-6 font-medium text-gray-900"
                >
                  Deactivate account
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to deactivate your account? All of your data will be permanently removed from our servers forever. This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
              <button
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                type="button"
                @click.self="close"
              >
                Deactivate
              </button>
              <button
                ref="cancelButtonRef"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                type="button"
                @click.self="close"
              >
                Cancel
              </button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {CheckIcon, ExclamationIcon} from '@heroicons/vue/outline'

export default {
  name: 'BaseModal',

  components: {
    Dialog,
    DialogOverlay,
    CheckIcon,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    ExclamationIcon,
  },

  emits: ['BaseModal:opened', 'BaseModal:closed'],

  // expose: ['isOpen'],

  data() {
    return {
      isOpen: false,
      onEscapeCloseModal: null,
    }
  },

  watch: {
    isOpen: {
      immediate: true,
      handler(isOpen) {
        isOpen
          ? document.body.style.setProperty('overflow', 'hidden')
          : document.body.style.removeProperty('overflow')
      },
    },
  },

  created() {
    this.onEscapeCloseModal = (e) => {
      if (e.keyCode === 27 && this.isOpen === true) {
        this.close()
      }
    }

    document.addEventListener('keydown', this.onEscapeCloseModal)
  },

  beforeUnmount() {
    document.removeEventListener('keydown', this.onEscapeCloseModal)

    // Makes sure the after the modal is closed to give back user ability to scroll the page
    document.body.style.removeProperty('overflow')
  },

  methods: {
    open() {
      this.isOpen = true

      this.$emit('BaseModal:opened')
    },
    close() {
      this.isOpen = false

      this.$emit('BaseModal:closed')
    },
  },
}
</script>