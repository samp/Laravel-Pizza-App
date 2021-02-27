 <template>
  <div>
      <h3 class="pb-2">Order placed succesfully!</h3>
      <div class="grid grid-cols-5 pl-5">
        <h4 class="col-span-2">
          <strong>{{ "Name" }}</strong>
        </h4>
        <h4>
          <strong>{{ "Size" }}</strong>
        </h4>
        <h4>
          <strong>{{ "Price" }}</strong>
        </h4>
      </div>
      <div
        class="leading-loose"
        v-for="(item, index) in cart"
        :key="`item-${index}`"
      >
        <div class="grid grid-cols-5 pl-5">
          <div class="col-span-2">
            <p>{{ item.name }}</p>
          </div>
          <div>
            <p>{{ item.size }}</p>
          </div>
          <div>
            <p>£{{ item.price.toFixed(2) }}</p>
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
      <p>{{ method }}</p>

      <br />

      <div>
        <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
      </div>

      <div class="text-center">
        <a
          href="/order"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
        >
          Order again
        </a>
      </div>

  </div>
</template>

    <script>
export default {
  props: ["method", "cart"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {
    };
  },
  computed: {
    orderTotal() {
      let total = 0;
      for (let item of this.cart) {
        total += item.price;
      }
      return total;
    }
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