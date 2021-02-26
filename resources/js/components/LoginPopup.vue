<template>
  <div>
    <!-- Mask -->
    <transition name="fade">
      <div
        class="fixed w-full h-full bg-gray-500 bg-opacity-75 inset-0"
        aria-hidden="true"
        @click="close"
        v-show="show"
      >
        <!-- Container -->
        <div class="flex justify-center items-center">
          <div
            class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
            @click.stop
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
                class="focus:outline-none text-white py-2.5 px-5 mx-2.5 rounded-md bg-blue-500 hover:bg-blue-600 opacity-100"
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
    </transition>
  </div>
</template>

<script>
export default {
  props: ["show"],
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
