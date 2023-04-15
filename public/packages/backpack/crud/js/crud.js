/*
*
* Backpack Crud
*
*/

jQuery(function($){

    'use strict';
});


var rtlChar = /[\u0590-\u083F]|[\u08A0-\u08FF]|[\uFB1D-\uFDFF]|[\uFE70-\uFEFF]/mg;
$(document).ready(function(){
    $('.checkRTL').keyup(function(){
        var isRTL = this.value.match(rtlChar);
        if(isRTL !== null) {
            this.style.direction = 'rtl';
         }
         else {
            this.style.direction = 'ltr';
         }
    });
});
