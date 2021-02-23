 <template>
  <div>
    <form method="POST" action="/order">
      <input type="hidden" name="_token" :value="csrf" />
      <h3 class="pb-2">Named Pizzas</h3>

      <div class="grid grid-cols-5 pl-5">
        <h4 class="col-span-2 pb-1">
          <strong>{{ "Name" }}</strong>
        </h4>

        <h4>
          <strong>{{ "Small" }}</strong>
        </h4>

        <h4>
          <strong>{{ "Medium" }}</strong>
        </h4>

        <h4>
          <strong>{{ "Large" }}</strong>
        </h4>
      </div>

      <fieldset class="pl-5">
        <div v-for="pizza in pizzas" :key="pizza.id">
          <br v-if="pizza.name == 'Create your own'" />
          <p v-if="pizza.name == 'Create your own'" class="text-center">or</p>
          <div class="grid grid-cols-5 leading-loose">
            <div class="col-span-2">
              <input
                class=""
                type="radio"
                name="pizzaRadios"
                :id="pizza.name"
                :value="pizza.name"
                v-model="selectedPizza"
                @change="calculateTotal"
              />
              <label class="" :for="pizza.name">{{ pizza.name }}</label>
            </div>
            <div>
              <label class="" :for="pizza.name">£{{ pizza.smallprice }}</label>
            </div>
            <div>
              <label class="" :for="pizza.name">£{{ pizza.mediumprice }}</label>
            </div>
            <div>
              <label class="" :for="pizza.name">£{{ pizza.largeprice }}</label>
            </div>
          </div>
          <div class="" v-if="pizza.name != 'Create your own'">
            <div class="pl-5">
              <label>{{
                pizza.toppings.split(",").join(", ") | capitalize
              }}</label>
            </div>
          </div>
        </div>
      </fieldset>
      <p class="text-red-600" v-if="this.errors.pizzaRadios">You must select a pizza.</p>

      <br />

      <h3 class="pb-2">Size</h3>
      <fieldset class="grid grid-cols-3 pl-5 leading-loose">
        <div class="">
          <input
            class=""
            type="radio"
            name="sizeRadios"
            id="small"
            value="Small"
            v-model="selectedSize"
            @change="calculateTotal"
          />
          <label class="" for="small">{{ "Small" }}</label>
        </div>
        <div class="">
          <input
            class=""
            type="radio"
            name="sizeRadios"
            id="medium"
            value="Medium"
            v-model="selectedSize"
            @change="calculateTotal"
          />
          <label class="" for="medium">{{ "Medium" }}</label>
        </div>
        <div class="">
          <input
            class=""
            type="radio"
            name="sizeRadios"
            id="large"
            value="Large"
            v-model="selectedSize"
            @change="calculateTotal"
          />
          <label class="" for="large">{{ "Large" }}</label>
        </div>
      </fieldset>
      <p class="text-red-600" v-if="this.errors.sizeRadios">You must select a size.</p>

      <br />

      <h3 v-if="selectedPizza == 'Create your own'">Toppings</h3>
      <fieldset class="grid grid-cols-3 pl-5" v-if="selectedPizza == 'Create your own'">
        <div class="" v-for="topping in toppings" :key="topping.id">
          <input
            class=""
            type="checkbox"
            name="toppingCheckboxes[]"
            :id="topping.name"
            :value="topping.name"
            v-model="selectedToppings"
            @change="calculateTotal"
          />
          <label class="" :for="topping.name">{{ topping.name }}</label>
        </div>
      </fieldset>

      <br />

      <div v-if="selectedPizza != ''">
        <h3 class="pb-2">Your order:</h3>
        <div class="pl-5 leading-loose">
          <p>Selected pizza: {{ selectedPizza }}</p>
          <p>Size: {{ selectedSize }}</p>
          <p v-if="selectedToppings.length > 0">
            Toppings: {{ lowercaseToppings.join(", ") | capitalize }}
          </p>
          <br />
          <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
        </div>
      </div>

      <div class="text-center">
        <button
          type="submit"
          class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
        >
          Add to cart
        </button>
      </div>
      <login-popup></login-popup>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "pizzas", "toppings", "errors"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      selectedPizza: "",
      selectedSize: "",
      selectedToppings: [],
      orderTotal: 0,
    };
  },
  computed: {
    lowercaseToppings() {
      let out = [];
      for (var i = 0; i < this.selectedToppings.length; i++) {
        out.push(this.selectedToppings[i].toLowerCase());
      }
      out = out.sort();
      return out;
    },
  },
  methods: {
    calculateTotal: function () {
      this.orderTotal = 0;
      for (var i = 0; i < this.pizzas.length; i++) {
        let pizza = this.pizzas[i];
        if (pizza.name == this.selectedPizza) {
          if (this.selectedSize == "Small") {
            this.orderTotal += parseFloat(pizza.smallprice);
            this.orderTotal += this.selectedToppings.length * 0.9;
          } else if (this.selectedSize == "Medium") {
            this.orderTotal += parseFloat(pizza.mediumprice);
            this.orderTotal += this.selectedToppings.length;
          } else if (this.selectedSize == "Large") {
            this.orderTotal += parseFloat(pizza.largeprice);
            this.orderTotal += this.selectedToppings.length * 1.15;
          }
        }
      }
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