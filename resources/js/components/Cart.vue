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

      <div>
        <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
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
      <div v-if="!isAuthed">
          
        <login-popup :show="showModal" @close="showModal = false" transition="fadeIn"></login-popup>
          
      </div>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "cart", "activedeals", "errors"],
  mounted() {
    console.log(this.errors);
    console.log(this.activedeals);
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      selectedMethod: "",
      showModal: false,
    };
  },
  computed: {
    orderTotal() {
      let total = 0;
      for (let item of this.cart) {
        total += item.price;
      }
      return total;
    },
    isAuthed() {
      if (this.auth_user == null) {
        return false;
      } else {
        return true;
      }
    },
  },
  methods: {},

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