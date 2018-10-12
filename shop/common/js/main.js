document.addEventListener('DOMContentLoaded', function(){
    
    if(jsonCart.length === 0){
         document.getElementById('qty').classList.add("disabled");
         document.getElementById('checkoutButton').classList.add("disabled");
    }

	var subtotal = document.getElementsByName('subtotal');
    var totalqty = document.getElementsByName('totalqty');
	for(var len = jsonCart.length, i = len-1 ; i >= 0; i--){
		(function(n){
		var cart = jsonCart[n];
		var qty = document.getElementById(`qty_${cart['code']}`);
//Change subtotal price each product by changing qty in Cart		
		qty.addEventListener('change', function (e){
			cart['qty'] = qty.value;
            //Get total price and qty in Cart
            var total = get_total();
            //set total price and qty to HTML
            for ( var x = 0, len = subtotal.length; x < len; x++ ) {
                subtotal[x].textContent = total['subtotal'].toFixed(2);
                totalqty[x].textContent = total['totalqty'];
            }
            if(total['totalqty']===0){
     	     document.getElementById('qty').classList.add("disabled");
             document.getElementById('checkoutButton').classList.add("disabled");
            }else{
     	     document.getElementById('qty').classList.remove("disabled");
             document.getElementById('checkoutButton').classList.remove("disabled");
     	     document.getElementById('cartLogo_qty').textContent = total['totalqty'];
            }     

            /* XMLHttpRequest (for javascript--> PHP) */ 
            xmlhttprequest();
        },  true);
        
// Delete items in Cart
	    var delete_item = document.getElementById(`delete_${cart['code']}`);
	    delete_item.addEventListener('click', function (e){
	    	 var item = document.getElementById(`item_${cart['code']}`);
	    	 var message = document.getElementById('message');
	    	 //Delete item form Cart Array
	    	 jsonCart.splice(jsonCart.indexOf(cart), 1);
	    	 //Delete item form display Cart
	    	 item.parentNode.removeChild(item);
	    	 //Delete 'added cart' message
	    	 message.style.display = 'none';
	    	 //Get total price and qty in Cart
             var total = get_total();
             //set total price and qty to HTML
             for ( var x = 0, len = subtotal.length; x < len; x++ ) {
                subtotal[x].textContent = total['subtotal'].toFixed(2);
                totalqty[x].textContent = total['totalqty'];
            }
                if(total['totalqty']===0){
     	          document.getElementById('qty').classList.add("disabled");
                  document.getElementById('checkoutButton').classList.add("disabled");
                }else{
     	          document.getElementById('qty').classList.remove("disabled");
                  document.getElementById('checkoutButton').classList.remove("disabled");
     	          document.getElementById('cartLogo_qty').textContent = total['totalqty'];
     	        }     
             /* XMLHttpRequest (for javascript--> PHP) */ 
             xmlhttprequest();
	    },  true);

      }(i));
	}  //end of loop

}, false);

/** [xmlhttprequest description]         */
/* XMLHttpRequest POST(for javascript--> PHP */
function xmlhttprequest(){
	/* Create XMLHttpRequest object (for javascript--> PHP) */ 
		var xhr = new XMLHttpRequest();

	    xhr.onreadystatechange = function () {
	    	if (xhr.readyState === 4){
	    		if(xhr.status === 200){
	    		}else{
	    	   window.alert('Server Error: ' + xhr.status);
	    	    }
	        }
        };

		xhr.open("POST", "http://localhost/kenkoh/shop/shop/cart_ajax.php", true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
		//Security for JSON haijack prevention 
        xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");

        xhr.send("cart=" + JSON.stringify(jsonCart));
};

/** [xmlhttprequest description]         */
/* Get total price and qty in Cart */
function get_total(){
            var subtotal = 0;
            var totalqty = 0;
            var total = [];
            for(var j = 0, len = jsonCart.length; j < len; j++){
        	    subtotal = subtotal + jsonCart[j]['price'] * jsonCart[j]['qty'];
        	    totalqty = totalqty + Number(jsonCart[j]['qty']);
            }
            var total = {subtotal:subtotal, totalqty:totalqty};
            return total;
        };









