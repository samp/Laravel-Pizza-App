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
          <br v-if="pizza.name == 'Create your own'">
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
          value="small"
        />
        <label class="form-check-label" for="small">{{ "Small" }}</label>
      </div>
      <div class="col-4">
        <input
          class="form-check-input"
          type="radio"
          name="sizeRadios"
          id="medium"
          value="medium"
        />
        <label class="form-check-label" for="medium">{{ "Medium" }}</label>
      </div>
      <div class="col-4">
        <input
          class="form-check-input"
          type="radio"
          name="sizeRadios"
          id="large"
          value="large"
        />
        <label class="form-check-label" for="large">{{ "Large" }}</label>
      </div>
    </fieldset>
    <br />

    <h3>Toppings</h3>
    <fieldset
      class="form-check container form-check-inline"
      style="display: flex; flex-flow: row wrap"
    >
      <div class="col-4" v-for="topping in toppings" :key="topping.id" style="">
        <input
          class="form-check-input"
          type="checkbox"
          name="toppingCheckboxes"
          :id="topping.name"
          :value="topping.name"
        />
        <label class="form-check-label" :for="topping.name">{{
          topping.name
        }}</label>
      </div>
    </fieldset>
    <br />
    <h3>Total:</h3>
  </div>
</template>

    <script>
export default {
  props: ["pizzas", "toppings"],
  mounted() {
    console.log(this.pizzas);
  },
};
</script>

<style scoped>
</style>