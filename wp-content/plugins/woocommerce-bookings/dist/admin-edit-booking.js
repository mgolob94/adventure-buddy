this["wc-bookings"]=this["wc-bookings"]||{},this["wc-bookings"]["admin-edit-booking"]=function(n){var e={};function t(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return n[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}return t.m=n,t.c=e,t.d=function(n,e,o){t.o(n,e)||Object.defineProperty(n,e,{enumerable:!0,get:o})},t.r=function(n){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},t.t=function(n,e){if(1&e&&(n=t(n)),8&e)return n;if(4&e&&"object"==typeof n&&n&&n.__esModule)return n;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:n}),2&e&&"string"!=typeof n)for(var r in n)t.d(o,r,function(e){return n[e]}.bind(null,r));return o},t.n=function(n){var e=n&&n.__esModule?function(){return n.default}:function(){return n};return t.d(e,"a",e),e},t.o=function(n,e){return Object.prototype.hasOwnProperty.call(n,e)},t.p="",t(t.s=253)}({114:function(n,e){n.exports=window.wp.i18n},253:function(n,e,t){var o=t(114);!function(n){function e(){var n=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return!1!==n&&moment(n,"YYYY-MM-DD",!0).isValid()}function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";0!==n.length&&alert(n)}n(document).ready((function(){"true"===new URLSearchParams(window.location.href).get("confirm")&&"confirmed"!==n("#_booking_status").val()&&confirm((0,o.__)("Confirm the booking?","woocommerce-bookings"))&&(n(" #_booking_status").val("confirmed"),n(" #post").submit())})),n('#woocommerce-booking-save input[name="save"]').on("click",(function(o){var r=n("#booking_start_date"),i=n("#booking_end_date"),a=r.val(),u=i.val();if(!e(a)||!e(u))return o.preventDefault(),void t(wc_bookings_admin_edit_booking_params.invalid_start_end_date);e(a)&&e(u)&&!function(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";return 0!==n.length&&0!==e.length&&!moment(e).isBefore(n)}(a,u)&&(o.preventDefault(),t(wc_bookings_admin_edit_booking_params.date_range_invalid))}))}(jQuery)}});
//# sourceMappingURL=admin-edit-booking.js.map