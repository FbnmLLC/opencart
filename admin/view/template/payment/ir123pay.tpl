<?php echo $header; ?>
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
            <h1><?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a
                        onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_merchant_id; ?></td>
                        <td><input type="text" name="ir123pay_merchant_id" value="<?php echo $ir123pay_merchant_id; ?>"
                                   style="direction:ltr;"/>
                            <br/>
                            <?php if ($error_merchant_id) { ?>
                            <span class="error"><?php echo $error_merchant_id; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_create; ?></td>
                        <td><input type="text" name="ir123pay_create" value="<?php echo $ir123pay_create; ?>"
                                   style="direction:ltr;"/>
                            <br/>
                            <?php if ($error_create) { ?>
                            <span class="error"><?php echo $error_create; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_verify; ?></td>
                        <td><input type="text" name="ir123pay_verify" value="<?php echo $ir123pay_verify; ?>"
                                   style="direction:ltr;"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_gateway; ?></td>
                        <td><input type="text" name="ir123pay_gateway" value="<?php echo $ir123pay_gateway; ?>"
                                   style="direction:ltr;"/>
                            <br/>
                            <?php if ($error_gateway) { ?>
                            <span class="error"><?php echo $error_gateway; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_order_status; ?></td>
                        <td><select name="ir123pay_order_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $ir123pay_order_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="ir123pay_status">
                                <?php if ($ir123pay_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="ir123pay_sort_order" value="<?php echo $ir123pay_sort_order; ?>"
                                   size="1"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>