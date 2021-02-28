 <template>
  <div>
    <form method="POST" action="/deals">
      <input type="hidden" name="_token" :value="csrf" />
      <h3 class="pb-2">Available deals</h3>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Two for One Tuesdays" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Two medium or large pizzas</li>
            <li>Charged at the price of the highest priced pizza selected</li>
            <li>Collection/delivery</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('twoforonetuesdays')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="twoforonetuesdays"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="twoforonetuesdays"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Three for Two Thursdays" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Three medium pizzas</li>
            <li>
              Charged at the price of the two highest priced pizzas selected
            </li>
            <li>Collection/delivery</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('threefortwothursdays')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="threefortwothursdays"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="threefortwothursdays"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Family Friday" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Four named medium pizzas for £30</li>
            <li>Collection only</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('familyfriday')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="familyfriday"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="familyfriday"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Two Large" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Two large named pizzas for £30</li>
            <li>Collection only</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('twolarge')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="twolarge"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="twolarge"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Two Medium" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Two medium named pizzas for £18</li>
            <li>Collection only</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('twomedium')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="twomedium"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="twomedium"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
      <div class="grid grid-cols-4 pl-5 pb-2">
        <div class="col-span-3">
          <h4>
            <strong>{{ "Two Small" }}</strong>
          </h4>
          <ul class="list-disc pl-5">
            <li>Two small named pizzas for £12</li>
            <li>Collection only</li>
          </ul>
        </div>
        <div class="text-right">
          <button
            v-if="activedeals.includes('twosmall')"
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-red-500 hover:bg-red-600"
            name="twosmall"
            value="remove"
          >
            Remove deal
          </button>
          <button
            v-else
            class="focus:outline-none text-white py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600"
            name="twosmall"
            value="add"
          >
            Select deal
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

    <script>
export default {
  props: ["activedealsobj"],
  mounted() {
    console.log(this.errors);
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    };
  },
  computed: {
    activedeals() {
      let deals = [];
      if (this.activedealsobj != null) {
        for (let i of this.activedealsobj) {
          deals.push(i);
        }
        return deals;
      } else {
        return [];
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