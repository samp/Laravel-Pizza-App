 <template>
  <div class="position-ref">
    <form @submit.prevent="submit">
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
          <div class="container-row">
            <div class="col">
              <label class="form-check-label">{{
                pizza.toppings.split(",").join(", ") | capitalize
              }}</label>
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
        <div class="col-4" v-for="topping in toppings" :key="topping.id">
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
      <div v-if="selectedPizza != ''">
        <h3>Your order:</h3>
        <p>Selected pizza: {{ selectedPizza }}</p>
        <p>Size: {{ selectedSize }}</p>
        <p v-if="selectedToppings.length > 0">
          Toppings: {{ lowercaseToppings.join(", ") | capitalize }}
        </p>
        <br />
        <h3>Total: {{ "£" + orderTotal.toFixed(2) }}</h3>
      </div>

      <div class="text-center">
        <button
          type="submit"
          class="btn btn-primary btn-lg"
          data-toggle="modal"
          data-target="#myModal"
        >
          Place order
        </button>
        <br v-if="(pizzaerror == true || sizeerror == true)" />
        <br v-if="(pizzaerror == true || sizeerror == true)" />
        <p class="text-danger mb-0" v-if="pizzaerror == true">
          You must select a pizza.
        </p>
        <p class="text-danger mb-0" v-if="sizeerror == true">
          You must select a size.
        </p>
      </div>
      <login-popup v-if="autherror == true"></login-popup>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["auth_user", "pizzas", "toppings"],
  mounted() {
    //console.log(this.auth_user);
  },
  data() {
    return {
      selectedPizza: "",
      selectedSize: "",
      selectedToppings: [],
      orderTotal: 0,
      autherror: false,
      pizzaerror: false,
      sizeerror: false,
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
    fields() {
      let out = {
        pizza: this.selectedPizza,
        size: this.selectedSize,
        toppings: this.selectedToppings,
      };
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
            this.orderTotal += this.selectedToppings.length * 1;
          } else if (this.selectedSize == "Large") {
            this.orderTotal += parseFloat(pizza.largeprice);
            this.orderTotal += this.selectedToppings.length * 1.15;
          }
        }
      }
    },
    submit() {
      this.errors = {};
      console.log(this.fields);
      if (this.fields.pizza == "") {
        this.pizzaerror = true;
      } else {
        this.pizzaerror = false;
      }

      if (this.fields.size == "") {
        this.sizeerror = true;
      } else {
        this.sizeerror = false;
      }
      if (this.pizzaerror == false && this.sizeerror == false) {
        axios
          .post("/submit", this.fields)
          .then((response) => {
            alert("Post OK");
          })
          .catch((error) => {
            if (error.response.status === 422) {
              this.errors = error.response.data.errors || {};
              console.log(this.errors);
            }
            if (error.response.status === 401) {
              this.errors = error.response.data.errors || {};
              this.autherror = true;
            }
          });
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