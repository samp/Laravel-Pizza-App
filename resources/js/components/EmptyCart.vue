 <template>
  <div>
    <p class="text-center">{{ "Your cart is currently empty!" }}</p>
    <br />
    <div class="text-center">
      <a
        href="/order"
        role="button"
        class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
      >
        Start an order
      </a>
      <div class="text-center" v-if="isAuthed">
        <br />
        <button
          type="button"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
          @click="SaveCart"
        >
          Save Order
        </button>
        <button
          type="button"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
          @click="LoadCart"
        >
          Load Order
        </button>
      </div>
    </div>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "savedorder"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {};
  },
  computed: {
    isAuthed() {
      if (this.auth_user == null) {
        return false;
      } else {
        return true;
      }
    },
  },
  methods: {
    SaveCart(e) {
      const axios = require("axios");
      e.preventDefault();
      let currentObj = this;
      axios
        .post("/savecart", {
          user: this.auth_user,
          cart: this.cart,
        })
        .then(function (response) {
          currentObj.output = response.data;
        })
        .catch(function (error) {
          currentObj.output = error;
        });
      console.log("posted");
    },
    LoadCart(e){
        //
    }
  },

  filters: {
    capitalize: function (value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>

<style scoped>
</style>