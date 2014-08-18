
<?php  echo $header; ?>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8/jquery.validate.min.js"></script>
<script type="text/javascript"> $(document).ready(function(){ $("#form").validate(); }); </script>
<script>
    function verify(){
        var user = $('#voodoo_username').val();
        var pass = $('#voodoo_password').val();
        var token = "<?php echo $_REQUEST['token']; ?>";
        var url  = "index.php?route=module/voodoo/verify_api&user="+user+"&pass="+pass+"&token="+token;
        $.ajax({
            type: "POST",
            url: url
        })
                .done(function( msg ) {
                    alert( msg );
                });

    }
</script>
<style>
    .custom-form input, textarea, select {
        width: 20%;
        border: 1px solid grey;
        padding: 5px;
        color: black;
        font-weight: normal;
    }

    .custom-form textarea {
        height: 100px;
        color: black;
        font-weight: normal;
    }

    .custom-button {
        border-radius: 0px !important;
        background: darkolivegreen !important;
        border: 1px solid #696969 !important;
    }

    .custom-button:hover {
        background: graytext !important;
    }
</style>
<div id="content">
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
<div class="heading">
    <h1><img src="view/image/magepal.png" style="height: 25px;width:100px;" alt=""/></h1>

    <div class="buttons">
        <a onclick="$('#form').submit();" class="button custom-button">
            <span><?php echo $button_save; ?></span>
        </a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button custom-button">
            <span><?php echo $button_cancel; ?></span>
        </a>
    </div>
</div>
<div class="content" style="background: #EAF0EE;">
    <img src="view/image/magepal.png"/><br>

    <h3>Voodoo SMS Extension v1.0.0 by Bulk SMS Limited</h3>

    <h3>Get an Account Now: <a target="_blank" href="http://www.voodoosms.com/register.html">www.voodoosms.com/register.html</a>
    </h3>

    <p>Find out more <a href="http://www.voodoosms.com/about-bulk-sms.html" target="_blank">about us</a></p>

    <p>Query? Feel free to ask <a href="mailto:info@voodoosms.com">info@voodoosms.com</a></p>

    <div id="tabs" class="htabs">
        <a href="#tab-api" style="display: inline;" class="">API Information</a>
        <a href="#tab-order" style="display: inline;">New Orders</a>
        <a href="#tab-status" style="display: inline;">Order Status</a>
        <a href="#tab-hold" style="display: inline;">Additional Options</a>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-api" style="display:none;">
            <table class="form custom-form">
                <tr>
                    <td><?php echo $entry_voodoo_balance; ?></td>
                    <td><?php echo $voodoo_balance; ?></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_voodoo_username; ?></td>
                    <td><input type="text" name="voodoo_username" class="required" id="voodoo_username" placeholder="API Username" value="<?php echo $voodoo_username; ?>"></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_voodoo_password; ?></td>
                    <td><input type="password" name="voodoo_password" class="required" id="voodoo_password" placeholder="API Password" value="<?php echo $voodoo_password; ?>"></td>
                </tr>
                <tr>
                    <td>
                       Verify API
                    </td>
                    <td>
                        <div class="buttons">
                            <a onclick="verify();" class="button custom-button">
                                <span>Verify your API</span>
                            </a>

                        </div>

                    </td>
                </tr>
            </table>
        </div>
        <div id="tab-order" style="display:none;">
            <table class="form">
                <tr class="custom-form">
                    <td><?php echo $order_enabled; ?></td>
                    <td>
                        <select name="order_enabled_value">
                            <option value="1"
                            <?php if($order_enabled_value==1):?> selected="selected" <?php endif; ?>>Yes</option>
                            <option value="2"
                            <?php if($order_enabled_value!=1):?> selected="selected" <?php endif; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr class="custom-form">
                    <td><span class="required">*</span> <?php echo $order_sender; ?></td>
                    <td><input type="text" class="required" name="order_sender_number" placeholder="Your Store" value="<?php echo $order_sender_number; ?>"></td>
                </tr>
                <tr class="custom-form">
                    <td><span class="required">*</span> <?php echo $order_message; ?></td>
                    <td><textarea class="required" cols="30" name="order_message_value"
                                  placeholder="Thank you for placing your order with www.yourdomain.com. Your order ID is {{order_id}} and is currently being processed. Your Store."><?php echo $order_message_value; ?></textarea>
                    </td>
                </tr>
                <tr style="display:none;">
                    <td>Include Ordered Item</td>
                    <td> <input type="checkbox" name="voodoo_admin_alert_include_items"
                                value="1" <?php echo ($voodoo_admin_alert_include_items==1?'checked':''); ?>
                        >  <?php echo $text_admin_alert_include_items; ?></td>
                </tr>
            </table>
        </div>
        <div id="tab-hold" style="display:none;">
            <table class="form">
                <tr>
                    <td><?php echo $notify_admin; ?></td>
                    <td>
                        <select id="notify_admin" name="notify_admin_value">
                            <option value="1"
                            <?php if($notify_admin_value==1):?> selected="selected" <?php endif; ?>>Yes</option>
                            <option value="2"
                            <?php if($notify_admin_value!=1):?> selected="selected" <?php endif; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr class="admin_telephone">
                    <td><span class="required">*</span> <?php echo $admin_telephone; ?></td>
                    <td class="custom-form">
                        <input type="text" name="admin_telephone_value" value="<?php echo $admin_telephone_value; ?>"
                               placeholder="ex: 447767123123"/>
                    </td>
                </tr>

                <tr class="admin_telephone">
                    <td><?php echo $entry_voodoo_admin_alert; ?></td>
                    <td>
                        <input type="checkbox" name="voodoo_admin_alert_customer_register"
                               value="1" <?php echo ($voodoo_admin_alert_customer_register==1?'checked':''); ?>
                        > <?php echo $text_admin_alert_customer_register; ?> <br>
                        <input type="checkbox" name="voodoo_admin_alert_customer_checkout"
                               value="1" <?php echo ($voodoo_admin_alert_customer_checkout==1?'checked':''); ?>
                        > <?php echo $text_admin_alert_customer_checkout; ?> <br>
                        <input type="checkbox" name="admin_alert_order_status"
                               value="1" <?php echo ($admin_alert_order_status==1?'checked':''); ?>
                        > <?php echo $text_admin_alert_order_status; ?> <br>
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab-status" style="display:none;">
            <table class="form">
                <tr>
                    <td>On Order Status Changed</td>
                    <td>
                        <select name="order_status_enabled">
                            <option value="1"
                            <?php if($order_status_enabled==1):?> selected="selected" <?php endif; ?>>Yes</option>
                            <option value="2"
                            <?php if($order_status_enabled!=1):?> selected="selected" <?php endif; ?>>No</option>
                        </select>
                    </td>

                </tr>
                <tr class="custom-form">
                    <td><span class="required">*</span> <?php echo $order_status_sender; ?></td>
                    <td><input class="required" type="text" name="order_status_sender_number" placeholder="Your Store" value="<?php echo $order_status_sender_number; ?>"></td>
                </tr>
                <tr class="custom-form">
                    <td><span class="required">*</span> Message</td>
                    <td><textarea class="required" name="message_of_order_status" placeholder="Your order ID {{order_id}} has been marked as {{order_status}}. Thanks for shopping at www.yourdomain.com"><?php echo $message_of_order_status;?></textarea></td>
                </tr>

            </table>
        </div>
    </form>
</div>
<script type="text/javascript"><!--
    $('#template').load('index.php?route=setting/setting/template&token=d98639f8532adc2796394f13e4852afd&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));
    //--></script>
<script type="text/javascript"><!--
    $('select[name=\'config_country_id\']').bind('change', function () {
        $.ajax({
            url: 'index.php?route=setting/setting/country&token=d98639f8532adc2796394f13e4852afd&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function () {
                $('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
            },
            complete: function () {
                $('.wait').remove();
            },
            success: function (json) {
                if (json['postcode_required'] == '1') {
                    $('#postcode-required').show();
                } else {
                    $('#postcode-required').hide();
                }

                html = '<option value=""> --- Please Select --- </option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '3563') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"> --- None --- </option>';
                }

                $('select[name=\'config_zone_id\']').html(html);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'config_country_id\']').trigger('change');
    //--></script>
<script type="text/javascript"><!--
    function image_upload(field, thumb) {
        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=d98639f8532adc2796394f13e4852afd&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: 'Image Manager',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=d98639f8532adc2796394f13e4852afd&image=' + encodeURIComponent($('#' + field).val()),
                        dataType: 'text',
                        success: function (data) {
                            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 800,
            height: 400,
            resizable: false,
            modal: false
        });
    }
    ;

    hideAllDivs = function () {
        $('.admin_telephone').hide();
    }
    handleNew = function () {
        hideAllDivs();
        var handler = ($(this).val());
        if (handler == 1) {
            $('.admin_telephone').show();
        }
        else {
            $('.admin_telephone').hide();
        }
    }
    $(document).ready(function () {

        $("#notify_admin").change(handleNew);

        // Run the event handler once now to ensure everything is as it should be
        handleNew.apply($("#notify_admin"));

    });
    //--></script>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
    //--></script>

</div>
<?php echo $footer; ?>