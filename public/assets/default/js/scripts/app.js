/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    
    $('[data-toggle="tooltip"]').tooltip();
    
    $('.btn-disabled').click(function(e){
        return false;
    });

})

