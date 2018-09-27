/**
 * Copyright Â© 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

require([
   'jquery',
    'jquery/ui',
    "mage/calendar"
], function ($) {

    // $('[name="product[booking_check_out_time]"]').click(function(){

    //     console.log('djhfx');
    //     // $('[name="product[booking_check_out_time]"]').datepicker();
    // });

   
    setTimeout(function() {

       //  $('[data-index="booking_check_out_time"]').on('click', '[name="product[booking_check_out_time]"]', function (event) {
       //      if ($('[name="product[booking_check_out_time]"]').length >0) {
       //          console.log($('[name="product[booking_check_out_time]"]').length);
       //      }
       //      console.log(document.getElementsByName('product[booking_check_out_time]').length);
       // });
        $('[name="product[booking_check_out_time]"]').timepicker();
   }, 5000);
    // $('[name="product[booking_check_out_time]"]').click(function(){

    //     console.log($('[name="product[booking_check_out_time]"]').length);
    // });

    

     //$('[name="product[booking_check_out_time]"]').timepicker();

});
