<?php

/** @var \Ilch\View $this */

$start_datetime = $this->escape($this->originalInput('start_datetime', $this->get('start_datetime')));
$end_datetime = $this->escape($this->originalInput('end_datetime', $this->get('end_datetime')));
?>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('start_datetime') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('start_datetime') ? ' has-error' : '' ?>">
        <label for="start_datetime" class="col-xl-2 col-form-label">
            <?=$this->getTrans('start_datetimeText') ?>:
        </label>
        <div class="col-xl-4 input-group ilch-date date form_datetime_1">
            <input type="text"
                   class="form-control"
                   id="start_datetime"
                   name="start_datetime"
                   value="<?=$start_datetime ?>" />
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>

    <h1><?=$this->getTrans('end_datetime') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('end_datetime') ? ' has-error' : '' ?>">
        <label for="end_datetime" class="col-xl-2 col-form-label">
            <?=$this->getTrans('end_datetimeText') ?>:
        </label>
        <div class="col-xl-4 input-group ilch-date date form_datetime_2">
            <input type="text"
                   class="form-control"
                   id="end_datetime"
                   name="end_datetime"
                   value="<?=$end_datetime ?>" />
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>

    <?=$this->getSaveBar('edit') ?>
</form>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    const start = new tempusDominus.TempusDominus(document.getElementById('start_datetime'), {
        display: {
            calendarWeeks: true,
            buttons: {
                today: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm",
        }
    });

    const end = new tempusDominus.TempusDominus(document.getElementById('end_datetime'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            calendarWeeks: true,
            buttons: {
                today: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm",
        }
    });

    start.subscribe('change.td', (e) => {
        end.updateOptions({
            restrictions: {
                minDate: e.date,
            },
        });
    });

    end.subscribe('change.td', (e) => {
        start.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
    });
});
</script>
