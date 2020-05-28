<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<?php
session_start();


?>

<form method="post">
	<h3>Account Details</h3>
    <input type="text" name="txtSortCode" id="txtSortCode" placeholder="Sort code" <?php if(isset($_POST['txtSortCode'])) { echo 'value="' . $_POST['txtSortCode'] . '"'; } else { echo 'value="774814"'; } ?>><br>
    <input type="text" name="txtAccNumber" id="txtAccNumber" placeholder="Account number" <?php if(isset($_POST['txtAccNumber'])) { echo 'value="' . $_POST['txtAccNumber'] . '"'; } else { echo 'value="24782346"'; } ?>><br>
    <div id="message"></div>
</form>	

<script type="text/javascript">

$(function() {
    
    var bankInvalid = []; //declared outside so available globally
    function validateBankNumber(sortcode, accnum) {
        $.ajax({
            url: '/func-ajax-modulus.php',
            type: 'POST',
            data: {
                SortCode: sortcode,
                AccNum: accnum
            },
            success: function(data) {
                var result = data.result;
                console.log(data);
                $('#message').html(result);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
    
    //onblur modulus check
    $('#txtAccNumber, #txtSortCode').bind('blur', function() {
        if ( $('#txtAccNumber').val() && $('#txtSortCode').val() ) {
            validateBankNumber($('#txtSortCode').val(), $('#txtAccNumber').val());
        }
    });
});

</script>

