<template>
  <div class="h-full border-8">
    <h2>Hello</h2>

    <button
      class="button-primary"
      @click="fetch"
    >
      Click
    </button>
    <pre>{{ isOpen }}</pre>
    <!--    <BaseModal ref="baseModal:alert" />-->
  </div>
</template>

<script>
// import {userAuthentication} from '@/http/authenticationApi.js'
import {userAuthentication} from '@/http/authenticationApi'

export default {
  name: 'App',


  methods: {
    async fetch() {
      // console.log(this.isOpen)

      // open()
      // alertModal.open()
      // this.$refs['baseModal:alert'].open()
      const response = await userAuthentication()

      console.log({response})
    },
  },
}
</script>

<style scoped>

</style>