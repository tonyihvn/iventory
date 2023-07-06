// SIDEBAR
$(document).ready(function(){
    $("#item").focus();

    $('.button-collapse').sideNav({
        menuWidth: 300, // Default is 300
        edge: 'left', // Choose the horizontal origin
        closeOnClick: false, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true // Choose whether you can drag to open on touch screens
      }
    );
    // START OPEN
    $('.button-collapse').sideNav('hide');

    // CONTROL ITEM SPECS HEADING
    $(".spechead").hide();

    // STOP CLOSURE ON OPEN
    $(document).on("click", ".select-wrapper", function (event) {
        event.stopPropagation();
    });

    //INITIALIZE SELECT2

    $('#search_product').select2({width: "100%"});

    $('#keyword').select2({width: "100%"});

    // $('select').material_select();

    setTimeout(function(){$('select').material_select();},1000);

    // $('select').formSelect();

    // INITIALIZE DATATABLES
    $('#example').DataTable( {
      "order": [[ 0, "desc" ]],
      columnDefs: [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        } ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );

    $('.page-link').addClass('btn btn-small');




    var table = $('#products').DataTable( {
        orderCellsTop: true,
        fixedHeader: false,
        "order": [[ 0, "asc" ]],
        "paging": true,
        "pageLength": 100,
        "filter": true,
        "ordering": true,
        deferRender: true,
        dom: 'Bfrtip',
        footer: false,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );

    $('.searchbox').keypress(function (e) {

        if (e.which == 13) {
            alert("Hola");
          $('#searchform').submit();
          // return false;    //<---- Add this line
        }
      });

    // TABLES WITH FILTERS
    $('#products thead tr').clone(true).appendTo( '#products thead' );
    $('#products thead tr:eq(1) th').each( function (i) {

        var title = $(this).text();

        // var title = titl.replace(/ +?/g, '');

        if(i==0 || title!='Actions'){
            $(this).html( '<input type="text" placeholder="Search '+title+'" value="" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }
    } );


    // INITIALIZE TOOL TIP
    $('.tooltipped').tooltip();

    // NEW SALES ITEM

    $('.add_item').click(function(){
        addItem("");
        reCalc();
        $("#search_product").focus();
    });

    $(".dt-button").click(function(){
        $('.print_table tr').find('th:last-child, td:last-child').remove();
        $(".dataTables_filter,.dataTables_info,.hide_on_print").remove();
        $("tr:eq(1)").remove();
        $('.dtsp-panesContainer').remove();
    });



    $("#quantity").on('keyup', function (e) {
        if (e.keyCode == 13) {
            addItem("");
            reCalc();
            $("#search_product").focus();
        }
    });

    $("#unit_cost").on('input','keyup', function (e) {
        if (e.keyCode == 13) {
            addItem("");
            reCalc();
            $("#search_product").focus();
        }
    });

    // CREATE SELECT ALL ITEMS
    $('#select-all').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $('.iselect').each(function() {
                this.checked = true;
            });
        } else {
            $('.iselect').each(function() {
                this.checked = false;
            });
        }
    });

    // PREVENT SUBMIT ON ENTER
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });


    $("#tax").on('input', function(){
        var tax = $("#tax").val();
        var sum = $("#total_due").val();
        var total_discount = $("#total_discount").val();
        if(total_discount==""){total_discount=0}
        if(tax==""){tax=0}
        var new_total = (parseFloat(sum)+parseFloat(tax)) - parseFloat(total_discount);

        $("#total_due").val(new_total.toFixed(2));
        reCalc();
    });

    $("#notearea").toggle();

    $("#add_notes").click(function(){
        $("#notearea").toggle();
    });
    $('.modal').modal();

    $('textarea#message').characterCounter();


    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        isRTL: true,
        format: 'yyyy-mm-dd',
        closeOnSelect: true // Close upon selecting a date,
   });

   $('.datepicker').on('mousedown',function(event){
        event.preventDefault();
    });

    $('#sales_form').each(function(){
        this.reset();
    });


}); // END DOCUMENT READY

function delItem(id,table){
    $.ajax({
        type: "POST",
        url: '/delete_item', // This is what I have updated
        data: { id: id, table: table }
    }).done(function( result ) {
        alert( result );
    });
}

$("#item").keyup(function (e) {
    if (e.keyCode == 13) {
        var product_id = $(this).val();
        var all_products = $("#allproducts").val();
        var all_items = $.parseJSON(all_products);
        var product = all_items.filter( obj => obj.product_id === product_id)[0];
        addItem(product);
        reCalc();
        $("#item").val("").focus();
    }
});


$('#quantity_returned').on('input', function(){

    var sp = $('#selling_price').val();
    var qs = $('#quantity_sold').val();
    var qr = $('#quantity_returned').val();
    var up = sp/qs;

    var ar = qr*up;

    $('#amount_returned').val(ar);
});

// ADD ITEM FUNCTION
function addItem(item){
    $(".spechead").show();
    var item_class = $(".add_item").attr("id");
      var old_class = parseFloat(item_class);
      new_class = old_class+1;

    $(".add_item").prop('id', new_class);

    $("table tbody#item_list").append("<tr scope='row' class='row"+new_class+"'><td class='input-field'><input type='text' name='property[]' value='' placeholder='e.g. Color, Brand etc'></td><td class='input-field'><td class='input-field'><input type='text' name='value[]' value='' placeholder='e.g. Red, HP etc'></td><td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow("+new_class+")'><i class='small material-icons'>remove</i></a></td></tr>");

};

// ADD ITEM FUNCTION
function addTools(){
    var item_class = $(".add_tool").attr("id");
      var old_class = parseFloat(item_class);
      new_class = old_class+1;

    $(".add_tool").prop('id', new_class);

    $("#dctool_select").append('<div class="row row'+new_class+'"><div class="input-field col s8"><input id="dctool" list="dctools" name="item[]" class="validate"><label for="items" class="active">Select Tool(s)</label></div><div class="input-field col s2"><input id="quantity" type="number" class="validate" name="quantity[]" value="1"><label for="quantity">Quantity Needed</label></div><div class="input-field col s2"><a href="#" class="btn-floating red btn-small delpos" onClick="delRow('+new_class+')">Remove</a></div></div>');

};

function changeAmount(clicked){
    // RECALCULATE AMOUNT OF ONE ITEM ON QUANTITY CHANGE
    var qty = $("#qty"+clicked).val();
    var unit_rate = $("#unit"+clicked).val();
    var new_amount = parseFloat(qty)*parseFloat(unit_rate);
    $("#amount"+clicked).val(new_amount.toFixed(2));
    reCalc();
}

function changeUc(clicked){
    // RECALCULATE AMOUNT OF ONE ITEM ON QUANTITY CHANGE
    var uc = $("#unit"+clicked).val();
    var qty = $("#qty"+clicked).val();

    var new_amount = parseFloat(qty)*parseFloat(uc);
    $("#amount"+clicked).val(new_amount.toFixed(2));
    reCalc();
}

function reCalc(){
    // RECALCULATE TOTAL AMOUNT
    var sum = 0;
    $(".amount").each(function(){
        sum += +$(this).val();
    });
    $("#total_due").val(sum.toFixed(2));


    // RECALCULATE TOTAL DISCOUNT
    var total_discount = 0;
    $(".item_discount").each(function(){
        total_discount += +$(this).val();
    });
    $("#total_discount").val(total_discount.toFixed(2));

    var tax = $("#tax").val();
    var total_discount = $("#total_discount").val();

    var new_sum = (parseFloat(sum)+parseFloat(tax)) - parseFloat(total_discount);

    $("#total_due").val(new_sum.toFixed(2));

    $("#amount_paid").val($("#total_due").val());

}

function delRow(rownum){
    $(".row"+rownum).remove();
    reCalc();
};



function addnumber(num){
    var all_num = $("#recipients").val();
    var new_num = $("#"+num).val();
    all_num = all_num+","+new_num;

    $("#recipients").val(all_num);
}

// PRINT A SPECIFIC PART OF A PAGE

function printtag(tagid) {
    $('#'+tagid).prepend('<div class="center"><img style="margin: auto; height: 70px; width: auto" src="/uploads/'+$('#'+tagid).attr("data-logo")+'" /></div><hr>');
    $('.print_table tr').find('th:last-child, td:last-child').remove();
    $(".dataTables_filter,.dataTables_info,.hide_on_print").remove();
    $("tr:eq(1)").remove();
    $('.dtsp-panesContainer').remove();

    var hashid = "#"+ tagid;
    var tagname =  $(hashid).prop("tagName").toLowerCase() ;
    var attributes = "";
    var attrs = document.getElementById(tagid).attributes;
      $.each(attrs,function(i,elem){
        attributes +=  " "+  elem.name+" ='"+elem.value+"' " ;
      })
    var divToPrint= $(hashid).html() ;
    var head = "<html><head>"+ $("head").html() + "</head>" ;
    var allcontent = head + "<body  onload='window.print()' >"+ "<" + tagname + attributes + ">" +  divToPrint + "</" + tagname + ">" +  "</body></html>"  ;
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write(allcontent);
    newWin.document.close();
   // setTimeout(function(){newWin.close();},10);
}

function viewFile() {
    $("#fileframe").attr("src",$('#viewfile').attr("data-src"));
    $("#filename").html($('#viewfile').attr("data-filename"));
}

// slight update to account for browsers not supporting e.which
function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };

function lockScreen(name) {
    $("#username").html('Hi '+name+'!');
    $("#notuser").html('Not '+name+'? Login');
    // $("#enter_password").toggle();
    // To disable f5
        /* jQuery < 1.7 */
    $(document).bind("keydown", disableF5);
    /* OR jQuery >= 1.7 */
    $(document).on("keydown", disableF5);

    // $( "#lockscreenModal" ).dialog({ closeOnEscape: false });
}



function closeModal(){
    $( "#lockscreenModal" ).modal("close");
}
