<?php

/**
 * EXTENSA
 *
 * Product Review Email Reminder for OpenCart 2.x
 *
 * @author    EXTENSA <info@extensadev.com>
 * @copyright Copyright (c) 2009-2016, Extensa Web Development OOD (http://extensadev.com/) All rights reserved.
 * @license   http://extensadev.com/licenses/LICENSE-EULA-extensions-en.txt EXTENSA Commercial Software License (EULA)
 * @package   EXTENSA\OpenCart extensions\Email Review
 * @link      http://extensadev.com/
 * @version   GIT: $Id$
 * @CID       $ClientID$
*/

?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-email-reminder" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email-reminder" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="enable"><?php echo $entry_alert_mail; ?></label>
              <div class="col-sm-10" >
              <?php if ($email_review_alert_mail) { ?>
                <input type="radio" name="email_review_alert_mail" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="email_review_alert_mail" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="email_review_alert_mail" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="email_review_alert_mail" value="0" checked="checked" />
                <?php echo $text_no; ?>
               <?php } ?>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-subjects"><?php echo $entry_subject; ?></label>
                <div class="col-sm-10">
                    <?php foreach ($languages as $language) { ?>
						<?php if(preg_match("/2.2.0/i", VERSION)){ ?>
                        <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>"  /><br />
						<?php }else{ ?>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"  /><br />
						<?php } ?>
                        <textarea class="form-control" name="email_review_subject_<?php echo $language['language_id']; ?>" ><?php echo isset(${'email_review_subject_' . $language['language_id']}) ? ${'email_review_subject_' . $language['language_id']} : ''; ?></textarea>
                  <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-statuses"><?php echo $entry_order_statuses; ?></label>
                <div class="col-sm-10">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                        <?php $class = 'even'; ?>
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                        <?php if ($email_review_order_statuses && in_array($order_status['order_status_id'], $email_review_order_statuses)) { ?>
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" value="<?php echo $order_status['order_status_id']; ?>" name="email_review_order_statuses[]" checked="checked" />
                                    <?php echo $order_status['name']; ?>
                                </label>
                            </div>
                        <?php } else { ?>
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" value="<?php echo $order_status['order_status_id']; ?>" name="email_review_order_statuses[]" />
                                    <?php echo $order_status['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-day"><?php echo $entry_order_day; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="email_review_day" value="<?php echo $email_review_day; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-test-email"><?php echo $entry_test_email; ?></label>
                <div class="col-sm-10">
                    <label class="col-sm-1 control-label" for="input-email"><?php echo $entry_email; ?></label>
                    <div class="col-sm-3">
                        <input type="text" id="test_email" name="email_review_test_email" class="form-control" />
                    </div>
                    <label class="col-sm-2 control-label" for="input-order-number"><?php echo $entry_order; ?></label>
                    <div class="col-sm-3">
                        <input type="text" id="test_order" name="email_review_test_order" class="form-control" />
                    </div>
                    <div class="col-sm-1">
                        <button id="button-filter" class="btn btn-primary pull-right" onclick="send_test()" type="button">send</button>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--    
function send_test() { 
    $.ajax({
        url: '<?php echo HTTP_CATALOG . 'cron_email_review.php'?>',
        type: 'post',
        data: 'email_review_test_email='+$('#test_email').val()+'&email_review_test_order=' + $('#test_order').val(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-send').attr('disabled', true);
            $('#button-send').before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
        },
        complete: function() {
            $('#button-send').attr('disabled', false);
            $('.wait').remove();
        },                
        success: function(json) {
        }
    });
}
//--></script> 
<?php echo $footer; ?>