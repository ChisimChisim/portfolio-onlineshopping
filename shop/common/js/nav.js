document.addEventListener('DOMContentLoaded', function(){
//Cart Qrt
    var totalqty = 0;
    if (undefined !== jsonCart){
     for(var i = 0, len = jsonCart.length; i < len; i++){
        	    totalqty = totalqty + Number(jsonCart[i]['qty']);
            }

     if(totalqty===0){
        if(!document.getElementById('qty').classList.contains("disabled")){
     	document.getElementById('qty').classList.add("disabled");
        }
     }else{
     	document.getElementById('qty').classList.remove("disabled");
     	document.getElementById('cartLogo_qty').textContent = totalqty;
     }  
    }

//login icon(change icon)
   if(undefined !== auth && auth==='1'){ //sign-in
    var login_icon = document.getElementById('logout-aside');
    var drawer = document.getElementById('drawer-logout');
   }else{ //no sign-in
    var login_icon = document.getElementById('login-aside');
    var drawer = document.getElementById('drawer-login');
   }

   login_icon.style.display='block';

//login icon (mouseOver -> side screen)
   login_icon.addEventListener('mouseover', function(){
        drawer.style.display = 'block';
   }, false);

   drawer.addEventListener('mouseover', function(){
      drawer.style.display = 'block';
   }, false);

   login_icon.addEventListener('mouseout', function(){  
      drawer.style.display = 'none';
   }, false);

   drawer.addEventListener('mouseout', function(){  
      drawer.style.display = 'none';
   }, false);



}, false);












