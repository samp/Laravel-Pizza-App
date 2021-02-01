 <template>
  <div class="position-ref">
    <h3>Named Pizzas</h3>
    <div class="form-check">
      <div class="container row">
        <div class="col-6">
          <h6>
            <strong>{{ "Name" }}</strong>
          </h6>
        </div>
        <div class="col-2">
          <h6>
            <strong>{{ "Small" }}</strong>
          </h6>
        </div>
        <div class="col-2">
          <h6>
            <strong>{{ "Medium" }}</strong>
          </h6>
        </div>
        <div class="col-2">
          <h6>
            <strong>{{ "Large" }}</strong>
          </h6>
        </div>
      </div>
    </div>

    <fieldset class="form-check">
      <div v-for="pizza in pizzas" :key="pizza.id">
        <br v-if="pizza.name == 'Create your own'" />
        <p v-if="pizza.name == 'Create your own'" class="text-center">or</p>
        <div class="container row">
          <br />
          <div class="col-6">
            <input
              class="form-check-input"
              type="radio"
              name="pizzaRadios"
              :id="pizza.name"
              :value="pizza.name"
              v-model="selectedPizza"
              @change="calculateTotal"
            />
            <label class="form-check-label" :for="pizza.name">{{
              pizza.name
            }}</label>
          </div>
          <div class="col-2">
            <label class="form-check-label" :for="pizza.name"
              >£{{ pizza.smallprice }}</label
            >
          </div>
          <div class="col-2">
            <label class="form-check-label" :for="pizza.name"
              >£{{ pizza.mediumprice }}</label
            >
          </div>
          <div class="col-2">
            <label class="form-check-label" :for="pizza.name"
              >£{{ pizza.largeprice }}</label
            >
          </div>
        </div>
      </div>
    </fieldset>
    <br />

    <h3>Size</h3>

    <fieldset
      class="form-check conatiner form-check-inline"
      style="display: flex; flex-flow: row wrap"
    >
      <div class="col-4">
        <input
          class="form-check-input"
          type="radio"
          name="sizeRadios"
          id="small"
          value="Small"
          v-model="selectedSize"
          @change="calculateTotal"
        />
        <label class="form-check-label" for="small">{{ "Small" }}</label>
      </div>
      <div class="col-4">
        <input
          class="form-check-input"
          type="radio"
          name="sizeRadios"
          id="medium"
          value="Medium"
          v-model="selectedSize"
          @change="calculateTotal"
        />
        <label class="form-check-label" for="medium">{{ "Medium" }}</label>
      </div>
      <div class="col-4">
        <input
          class="form-check-input"
          type="radio"
          name="sizeRadios"
          id="large"
          value="Large"
          v-model="selectedSize"
          @change="calculateTotal"
        />
        <label class="form-check-label" for="large">{{ "Large" }}</label>
      </div>
    </fieldset>
    <br />

    <h3 v-if="selectedPizza == 'Create your own'">Toppings</h3>
    <fieldset
      class="form-check container form-check-inline"
      style="display: flex; flex-flow: row wrap"
      v-if="selectedPizza == 'Create your own'"
    >
      <div
        class="col-4"
        v-for="topping in toppings"
        :key="topping.id"
      >
        <input
          class="form-check-input"
          type="checkbox"
          name="toppingCheckboxes"
          :id="topping.name"
          :value="topping.name"
          v-model="selectedToppings"
          @change="calculateTotal"
        />
        <label class="form-check-label" :for="topping.name">{{
          topping.name
        }}</label>
      </div>
    </fieldset>
    <br />
    <h3>Your order:</h3>
    <p>Selected pizza: {{ selectedPizza }}</p>
    <p>Selected size: {{ selectedSize }}</p>
    <p>Selected topping: {{ selectedToppings.join(", ") }}</p>
    <br />
    <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
    <div class="text-center">
      <button type="submit" class="btn btn-primary btn-lg">Order</button>
    </div>
  </div>
</template>

    <script>
export default {
  props: ["pizzas", "toppings"],
  mounted() {
    //console.log("");
  },
  data() {
    return {
      selectedPizza: "",
      selectedSize: "",
      selectedToppings: [],
      orderTotal: 0,
    };
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
            this.orderTotal += this.selectedToppings.length * 1;
          } else if (this.selectedSize == "Large") {
            this.orderTotal += parseFloat(pizza.largeprice);
            this.orderTotal += this.selectedToppings.length * 1.15;
          }
        }
      }
    },
  },
};
</script>

<style scoped>
</style>