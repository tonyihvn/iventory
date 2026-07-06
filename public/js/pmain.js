// Wait for libraries to be ready before initializing
document.addEventListener('librariesReady', function() {
    console.log('librariesReady event received - calling initializeApp');
    initializeApp();
});

// Also run initialization if event already fired (libraries loaded before this script)
if (typeof jQuery !== 'undefined' && jQuery.fn.select2 !== undefined) {
    console.log('jQuery and Select2 already loaded - dispatching librariesReady');
    document.dispatchEvent(new Event('librariesReady'));
} else {
    console.log('Waiting for librariesReady event - jQuery available:', typeof jQuery !== 'undefined');
}

function initializeApp() {
    console.log('initializeApp called');
    // Wrap everything in a document ready to ensure DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runApp);
    } else {
        console.log('DOM already ready - calling runApp');
        runApp();
    }
}

function runApp() {
    console.log('runApp() started');
    try {
// SIDEBAR - with fallback for when Materialize is not available
        $("#item").focus();

        try {
            if (typeof jQuery.fn.sideNav !== 'undefined') {
                // Materialize sideNav available
                $('.button-collapse').sideNav({
                    menuWidth: 300,
                    edge: 'left',
                    closeOnClick: false,
                    draggable: true
                });
            } else {
                // Fallback: Manual sidebar toggle
                console.log('sideNav not available, using fallback');
                initFallbackSidebar();
            }
        } catch(e) {
            console.log('Materialize sideNav not available, using fallback');
            initFallbackSidebar();
        }

    // CONTROL ITEM SPECS HEADING
    $(".spechead").hide();

    // STOP CLOSURE ON OPEN
    $(document).on("click", ".select-wrapper", function (event) {
        event.stopPropagation();
    });

    //INITIALIZE SELECT2
    try {
        if (jQuery.fn.select2 !== undefined) {
            $('#search_product').select2({width: "100%"});
            $('#keyword').select2({width: "100%"});
            
            // Initialize/reinitialize all select2 dropdowns with proper configuration
            $('.select2').each(function() {
                try {
                    const $this = $(this);
                    // Destroy any existing Select2 instance first
                    if ($this.hasClass('select2-hidden-accessible')) {
                        $this.select2('destroy');
                    }
                    // Reinitialize with proper configuration
                    $this.select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: 'Select an option',
                        minimumResultsForSearch: 0
                    });
                } catch(e) {
                    console.log('Select2 init error on element:', e);
                }
            });
        }
    } catch(e) {
        console.log('Select2 not available yet');
    }

    // $('select').material_select();

    setTimeout(function(){
        try {
            if (jQuery.fn.material_select !== undefined) {
                // Do NOT run Materialize's material_select on Select2 elements,
                // otherwise both widgets stack on the same <select> and their
                // values get out of sync (fields appear empty on submit).
                $('select').not('.select2').not('.select2-hidden-accessible').material_select();
            }
        } catch(e) {
            console.log('material_select not available');
        }
    }, 1000);

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

    // TABLES WITH FILTERS
    const table = $('#products').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "order": [[ 0, "asc" ]],
        "paging": false,
        "pageLength": 50,
        "filter": true,
        "ordering": true,
        deferRender: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ]
    } );

    $('#products thead tr').clone(true).appendTo( '#products thead' );
    $('#products thead tr:eq(1) th').each( function (i) {

        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" value="" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );




    // INITIALIZE TOOL TIP
    try {
        if (jQuery.fn.tooltip !== undefined) {
            $('.tooltipped').tooltip();
        }
    } catch(e) {
        console.log('Tooltip not available');
    }


    // NEW SALES ITEM

    $('.add_item').click(function(){
        addItem("");
        reCalc();
        $("#search_product").focus();
    });

    $('.add_tool').click(function(){
        addTools(1);
        reCalc();
        $("#search_product").focus();
    });

    $(".dt-button").click(function(){
        $('.print_table tr').find('th:last-child, td:last-child').remove();
        $(".dataTables_filter,.dataTables_info,.hide_on_print").remove();
        $("tr:eq(1)").remove();
        $('.dtsp-panesContainer').remove();
    });

    // MODAL HANDLING
    try {
        if (jQuery.fn.modal !== undefined) {
            $('.modal').modal();
        }
    } catch(e) {
        console.log('Modal not available');
    }

    // CHARACTER COUNTER
    try {
        if (jQuery.fn.characterCounter !== undefined) {
            $('textarea#message').characterCounter();
        }
    } catch(e) {
        console.log('Character counter not available');
    }

    // DATEPICKER
    try {
        if (jQuery.fn.pickadate !== undefined) {
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
        }
    } catch(e) {
        console.log('Datepicker not available');
    }

    $('#sales_form').each(function(){
        this.reset();
    });

    } catch(e) {
        console.log('Error in runApp:', e);
    }
} // END runApp function

// Fallback sidebar handler using vanilla JavaScript
function initFallbackSidebar() {
    const menuButton = document.querySelector('.button-collapse');
    const sidebar = document.getElementById('mobile-demo');
    let sidebarOpen = false;
    
    if (!menuButton || !sidebar) {
        console.log('Sidebar elements not found');
        return;
    }
    
    console.log('Sidebar fallback initialized');
    
    // Add click handler to menu button
    menuButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!sidebarOpen) {
            // Open sidebar
            sidebar.style.setProperty('transform', 'translateX(0)', 'important');
            sidebar.style.setProperty('z-index', '9999', 'important');
            
            // Add overlay
            let overlay = document.querySelector('.sidenav-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'sidenav-overlay';
                overlay.style.cssText = `
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    right: 0 !important;
                    bottom: 0 !important;
                    background: rgba(0,0,0,0.5) !important;
                    z-index: 998 !important;
                    cursor: pointer !important;
                `;
                overlay.addEventListener('click', function() {
                    menuButton.click();
                });
                document.body.appendChild(overlay);
            }
            sidebarOpen = true;
            console.log('Sidebar opened');
        } else {
            // Close sidebar
            sidebar.style.setProperty('transform', 'translateX(-300px)', 'important');
            
            // Remove overlay
            const overlay = document.querySelector('.sidenav-overlay');
            if (overlay) {
                overlay.parentNode.removeChild(overlay);
            }
            sidebarOpen = false;
            console.log('Sidebar closed');
        }
    });
    
    // Close sidebar when clicking links
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (sidebarOpen && !link.classList.contains('collapsible-header')) {
                menuButton.click();
            }
        });
    });
}

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
function addTools(item){
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
    $("#enter_password").toggle();
    // To disable f5
        /* jQuery < 1.7 */
    $(document).bind("keydown", disableF5);
    /* OR jQuery >= 1.7 */
    $(document).on("keydown", disableF5);

    $( "#lockscreenModal" ).dialog({ closeOnEscape: false });
}

function storeLock(){
    var locker = $("#password").val();
    Cookies.set('locker',locker);
    alert("Test");
}

function showForm(){
    $("#enter_password").toggle();
}
