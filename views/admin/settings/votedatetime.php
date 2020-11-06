<?php
$start_datetime = $this->escape($this->originalInput('start_datetime', $this->get('start_datetime')));
$end_datetime = $this->escape($this->originalInput('end_datetime', $this->get('end_datetime')));
?>
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('start_datetime') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('start_datetime') ? 'has-error' : '' ?>">
        <label for="start_datetime" class="col-lg-2 control-label">
            <?=$this->getTrans('start_datetimeText') ?>:
        </label>
        <div class="col-lg-4 input-group ilch-date date form_datetime_1">
            <input type="text"
                   class="form-control"
                   id="start_datetime"
                   name="start_datetime"
                   value="<?=$start_datetime ?>" />
            <span class="input-group-addon">
                <span class="fas fa-calendar"></span>
            </span>
        </div>
    </div>

    <h1><?=$this->getTrans('end_datetime') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('end_datetime') ? 'has-error' : '' ?>">
        <label for="end_datetime" class="col-lg-2 control-label">
            <?=$this->getTrans('end_datetimeText') ?>:
        </label>
        <div class="col-lg-4 input-group ilch-date date form_datetime_2">
            <input type="text"
                   class="form-control"
                   id="end_datetime"
                   name="end_datetime"
                   value="<?=$end_datetime ?>" />
            <span class="input-group-addon">
                <span class="fas fa-calendar"></span>
            </span>
        </div>
    </div>

    <?=$this->getSaveBar('edit') ?>
</form>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    $('.form_datetime_1').datetimepicker({
        <?=($end_datetime ? 'endDate: "'.$end_datetime.'",' : '') ?>
        format: "dd.mm.yyyy hh:ii",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        todayBtn: true,
        todayHighlight: true
    }).on('changeDate', function(ev){
        var mewDate = new Date(ev.date.valueOf());
        $('.form_datetime_2').datetimepicker('setStartDate', mewDate)
            .datetimepicker('update');
    });
    $('.form_datetime_2').datetimepicker({
        <?=($start_datetime ? 'startDate: "'.$start_datetime.'",' : '') ?>
        useCurrent: false, //Important! See issue #1075
        format: "dd.mm.yyyy hh:ii",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        todayBtn: true,
        todayHighlight: true
    }).on('changeDate', function(ev){
        var mewDate = new Date(ev.date.valueOf());
        $('.form_datetime_1').datetimepicker('setEndDate', mewDate)
            .datetimepicker('update');
    });

});
</script>
