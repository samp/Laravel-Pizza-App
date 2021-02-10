 <template>
  <div class="position-ref">
    <form method="POST" action="/order">
      <input type="hidden" name="_token" :value="csrf" />
      <h3>Your order</h3>
      <div class="container row">
        <div class="col-6">
          <h6>
            <strong>{{ "Name" }}</strong>
          </h6>
        </div>
        <div class="col-3">
          <h6>
            <strong>{{ "Size" }}</strong>
          </h6>
        </div>
        <div class="col-3">
          <h6>
            <strong>{{ "Price" }}</strong>
          </h6>
        </div>
      </div>
      <div v-for="(item, index) in cart" :key="`item-${index}`">
        <div class="container row">
          <div class="col-6">
            <p>{{ item.name }}</p>
          </div>
          <div class="col-3">
            <p>{{ item.size }}</p>
          </div>
          <div class="col-3">
            <p>£{{ item.price }}</p>
          </div>
        </div>
        <div class="container row">
          <div class="col">
            <p>Toppings: {{ item.toppings.join(", ") | capitalize }}</p>
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
      <p class="text-danger mb-0 pl-3">You must select a delivery method.</p>

      <br />

      <div>
        <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">
          Place Order
        </button>
      </div>
      <login-popup></login-popup>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["cart"],
  mounted() {
    console.log(this.cart);
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
  },
  methods: {},

  filters: {},
};
</script>

<style scoped>
</style>