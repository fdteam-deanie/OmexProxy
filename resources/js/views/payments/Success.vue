<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="message-box _success">
          <h2> Your payment was successful </h2>
          <p> Thank you for your payment<br>
           </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
._success {
  box-shadow: 0 15px 25px #00000019;
  padding: 45px;
  width: 100%;
  text-align: center;
  margin: 40px auto;
  border-bottom: solid 4px #28a745;
}

._success i {
  font-size: 55px;
  color: #28a745;
}

._success h2 {
  margin-bottom: 12px;
  font-size: 40px;
  font-weight: 500;
  line-height: 1.2;
  margin-top: 10px;
}

._success p {
  margin-bottom: 0px;
  font-size: 18px;
  color: #495057;
  font-weight: 500;
}
</style>