 <template>
  <div>
    <form method="POST" action="/cart">
      <input type="hidden" name="_token" :value="csrf" />
      <h3 class="pb-2">Your order</h3>
      <div class="grid grid-cols-10 pl-5">
        <h4 class="col-span-5">
          <strong>{{ "Name" }}</strong>
        </h4>
        <h4 class="col-span-2">
          <strong>{{ "Size" }}</strong>
        </h4>
        <h4 class="col-span-2">
          <strong>{{ "Price" }}</strong>
        </h4>
      </div>
      <div
        class="leading-loose"
        v-for="(item, index) in cart"
        :key="`item-${index}`"
      >
        <div class="grid grid-cols-10 pl-5">
          <div class="col-span-5">
            <p>{{ item.name }}</p>
          </div>
          <div class="col-span-2">
            <p>{{ item.size }}</p>
          </div>
          <div class="col-span-2">
            <p>£{{ item.price.toFixed(2) }}</p>
          </div>
          <div class="col-span-1">
            <a class="text-red-600">{{ "Delete" }}</a>
          </div>
        </div>

        <div class="pl-5">
          <p v-if="item.toppings">
            Toppings: {{ item.toppings.join(", ") | capitalize }}
          </p>
          <p v-else>Toppings: {{ "No toppings selected." }}</p>
        </div>
      </div>
      <br />
      <h3 class="pb-2">Delivery Method</h3>
      <fieldset class="grid grid-cols-2 pl-5 leading-loose">
        <div>
          <input
            class="form-check-input"
            type="radio"
            name="deliveryRadios"
            id="collection"
            value="Collection"
            v-model="selectedMethod"
          />
          <label class="form-check-label" for="collection">{{
            "Collection"
          }}</label>
        </div>
        <div>
          <input
            class="form-check-input"
            type="radio"
            name="deliveryRadios"
            id="delivery"
            value="Delivery"
            v-model="selectedMethod"
          />
          <label class="form-check-label" for="delivery">{{
            "Delivery"
          }}</label>
        </div>
      </fieldset>
      <p class="text-red-600" v-if="this.errors.deliveryRadios">
        You must select a delivery method.
      </p>

      <br />
      <h3>Deals</h3>
      <div v-if="activedeals != null">
        <input type="hidden" name="deals" :value="JSON.stringify(activedeals)" />
        <div
          class="leading-loose"
          v-for="(status, deal) in activedeals"
          :key="deal.keys"
        >
          <div class="grid grid-cols-10 pl-5">
            <div class="col-span-5">
              <p>{{ deal }}</p>
            </div>
            <div class="col-span-2">
              <p v-if="status == true">{{ "Deal applied" }}</p>
              <p v-if="status == false" class="text-red-600">
                {{ "Conditions not met" }}
              </p>
            </div>
          </div>
        </div>
        <p class="text-red-600" v-if="this.errors.deals">
          Please check deal requirements.
        </p>
      </div>
      <div v-else class="leading-loose pl-5">
        <p>No deals selected.</p>
      </div>
      <br />
      <div>
        <h3>Total: {{ "£" + finalprice.toFixed(2) }}</h3>
      </div>

      <div class="text-center" v-if="isAuthed">
        <button
          type="submit"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
        >
          Place Order
        </button>
      </div>
      <div class="text-center" v-else>
        <button
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
          @click="showModal = true"
          type="button"
        >
          Place Order
        </button>
      </div>
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
        <button
          type="button"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
          @click="DeleteCart"
        >
          Delete saved order
        </button>
        <div v-if="saveStatus">
          <br />
          <p>{{ saveStatus }}</p>
        </div>
      </div>
      <div v-if="!isAuthed">
        <login-popup
          :show="showModal"
          @close="showModal = false"
          transition="fadeIn"
        ></login-popup>
      </div>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "cart", "activedeals", "finalprice", "errors"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      selectedMethod: "",
      showModal: false,
      saveStatus: null,
    };
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
      axios
        .post("/savecart", {
          user: this.auth_user,
          cart: this.cart,
        })
        .then((response) => {
          this.saveStatus = response.data;
        })
        .catch(function (error) {
          this.saveStatus = error;
        });
      console.log("posted save");
    },
    LoadCart(e) {
      const axios = require("axios");
      e.preventDefault();
      axios
        .post("/loadcart", {
          user: this.auth_user,
          cart: this.cart,
        })
        .then((response) => {
          this.saveStatus = response.data;
        })
        .catch(function (error) {
          this.saveStatus = error;
        });
      console.log("posted load");
    },
    DeleteCart(e) {
      const axios = require("axios");
      e.preventDefault();
      axios
        .post("/deletecart", {
          user: this.auth_user,
          cart: this.cart,
        })
        .then((response) => {
          this.saveStatus = response.data;
        })
        .catch(function (error) {
          this.saveStatus = error;
        });
      console.log("posted delete");
    },
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