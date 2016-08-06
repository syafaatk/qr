/*********************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 *********************************************************************/

jQuery(document).ready(function($) {
    $(".link-row").click(function() {
        window.document.location = $(this).data("link");
    });
});