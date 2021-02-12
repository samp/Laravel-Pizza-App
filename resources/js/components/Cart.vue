 <template>
  <div class="position-ref">
    <form method="POST" action="/cart">
      <input type="hidden" name="_token" :value="csrf" />
      <h3>Your order</h3>
      <div class="container row">
        <div class="col-6">
          <h6>
            <strong>{{ "Name" }}</strong>
          </h6>
        </div>
        <div class="col-2">
          <h6>
            <strong>{{ "Size" }}</strong>
          </h6>
        </div>
        <div class="col-2">
          <h6>
            <strong>{{ "Price" }}</strong>
          </h6>
        </div>
      </div>
      <div v-for="(item, index) in cart" :key="`item-${index}`">
        <div class="container row">
          <div class="col-6">
            <p class="mb-0">{{ item.name }}</p>
          </div>
          <div class="col-2">
            <p class="mb-0">{{ item.size }}</p>
          </div>
          <div class="col-2">
            <p class="mb-0">£{{ item.price.toFixed(2) }}</p>
          </div>
          <div class="col-2">
            <a class="mb-0 text-danger">{{ "Delete" }}</a>
          </div>
        </div>
        <div class="container row">
          <div class="col">
            <p v-if="item.toppings">
              Toppings: {{ item.toppings.join(", ") | capitalize }}
            </p>
            <p v-else>Toppings: {{ "No toppings selected." }}</p>
          </div>
        </div>
      </div>
      <br />
      <h3>Delivery Method</h3>
      <fieldset
        class="form-check container form-check-inline"
        style="display: flex; flex-flow: row wrap"
      >
        <div class="col-4">
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
        <div class="col-8">
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
      <p class="text-danger mb-0 pl-3" v-if="this.errors.deliveryRadios">
        You must select a delivery method.
      </p>

      <br />

      <div>
        <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
      </div>

      <div class="text-center" v-if="isAuthed">
        <button
          type="submit"
          class="btn btn-primary btn-lg"
        >
          Place Order
        </button>
      </div>
      <div class="text-center" v-else>
        <button
          class="btn btn-primary btn-lg"
          data-toggle="modal"
          data-target="#loginModal"
          type="button"
        >
          Place Order
        </button>
      </div>
      <div v-if="!isAuthed">
        <login-popup></login-popup>
      </div>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "cart", "errors"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      selectedMethod: "",
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