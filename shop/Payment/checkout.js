document.addEventListener('DOMContentLoaded', function(){
//Dispay rewards if Sign-in
if(undefined !== auth && auth==='1'){ //sign-in
    document.getElementById('applyRewards').classList.remove("disabled");
   }else{ //no sign-in
    if(!document.getElementById('applyRewards').classList.contains("disabled")){
        document.getElementById('applyRewards').classList.add("disabled");
        }
   }


/**************  STRIPE API  SART*******************/
var handler = StripeCheckout.configure({
    key: 'pk_test_**********************',  //publishable key
    image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
    locale: 'auto',
    token: function(token, address) {

    //prevent user to leave thepayment page
    window.addEventListener('beforeunload', function(e) {
    e.returnValue = "Are you sure you want leave?";
    }, false);  

    // Dynamically create a form element to submit the results
    // to your backend server
    var form = document.createElement("form");
    form.setAttribute('method', "POST");
    form.setAttribute('action', "../payment/charge.php");

    // Add the token ID as a hidden field to the form
    var inputToken = document.createElement("input");
    inputToken.setAttribute('type', "hidden");
    inputToken.setAttribute('name', "stripeToken");
    inputToken.setAttribute('value', token.id);
    form.appendChild(inputToken);

    // Add the email as a hidden field to the form
    var inputEmail = document.createElement("input");
    inputEmail.setAttribute('type', "hidden");
    inputEmail.setAttribute('name', "stripeEmail");
    inputEmail.setAttribute('value', token.email);
    form.appendChild(inputEmail);

    //Add Total price
    var inputTotal = document.createElement("input");
    inputTotal.setAttribute('type', "hidden");
    inputTotal.setAttribute('name', "totalPrice");
    inputTotal.setAttribute('value', (checkout_total).toFixed(0));
    form.appendChild(inputTotal);

    var inputAddress = document.createElement("input");
    inputAddress.setAttribute('type', "hidden");
    inputAddress.setAttribute('name', "address");
    inputAddress.setAttribute('value', JSON.stringify(address));
    form.appendChild(inputAddress);

    // Finally, submit the form
    document.body.appendChild(form);

    // Artificial 1 second delay for testing
    setTimeout(function() {
        window.onbeforeunload = null;
        form.submit();
    }, 1000);
}

});

  document.getElementById('paymentButton').addEventListener('click', function(e) {
  // Open Checkout with further options
  handler.open({
    name: 'Kenkoh',
    description: 'Total: $' + (checkout_total/100).toFixed(2) + ' (Qty: ' + checkout_qty + ')',
    panelLabel:'Pay with credit',
    currency:'USD',
    billingAddress:true,
    shippingAddress:true,
    zipCode: true,
    amount: checkout_total,
  });
  e.preventDefault();
});

// Close Checkout on page navigation
window.addEventListener('popstate', function() {
  handler.close();
});

/**************  STRIPE API  END*******************/

}, false);

