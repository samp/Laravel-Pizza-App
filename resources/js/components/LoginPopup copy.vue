<template>
  <div>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-10 inset-0 overflow-y-auto">
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
        <div
          class="fixed inset-0 transition-opacity"
          aria-hidden="true"
          @click="close"
        >
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
          >&#8203;</span
        >
        <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-headline"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3
                  class="leading-6 font-medium text-gray-900 py-2"
                  id="modal-headline"
                >
                  {{ "Log in" }}
                </h3>

                <p class="text-gray-500 mt-2">
                  {{ "You must be logged in to place an order" }}
                </p>
              </div>
            </div>
          </div>
          <div
            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-center"
          >
            <a
              href="/login"
              role="button"
              class="focus:outline-none text-white py-2.5 px-5 mx-2.5 rounded-md bg-blue-500 hover:bg-blue-600"
            >
              {{ "Log in" }}
            </a>
            <a
              href="/register"
              role="button"
              class="focus:outline-none text-white py-2.5 px-5 mx-2.5 rounded-md bg-blue-500 hover:bg-blue-600"
            >
              {{ "Register" }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    //console.log("Component mounted.");
  },
  methods: {
    // ...
    close: function () {
      this.$emit("close");
    },
  },
};
</script>
