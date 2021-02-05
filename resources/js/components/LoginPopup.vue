<template>
  <div>
    <div class="modal fade" id="myModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="card-header">{{ "Login" }}</div>

          <div class="card-body">
            <p>You must be logged in to place an order.</p>
            <div class="d-flex justify-content-around"><a role="button" class="btn btn-primary" href="/login">Login</a><a role="button" class="btn btn-primary" href="/register">Register</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
