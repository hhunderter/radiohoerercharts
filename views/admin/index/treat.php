<?php

/** @var \Ilch\View $this */

/** @var bool $show_artwork */
$show_artwork = $this->get('show_artwork');

/** @var Modules\RadioHoererCharts\Models\HoererCharts $entry */
$entry = $this->get('entry');
?>
<h1><?=($entry != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('datecreate') ?>
        </div>
        <div class="col-lg-4">
            <?php
            if ($entry != '') {
                $datenow = new \Ilch\Date($entry->getDateCreate());
                echo $datenow->format($this->getTrans('datetimeformat'));
            } else {
                echo $this->getTrans('new');
            }
            ?>
        </div>
    </div>
    <?php if (!$this->getRequest()->getParam('suggestion')) { ?>
    <div class="form-group <?=$this->validation()->hasError('setfree') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('setfree') ?>
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="setfree-yes" name="setfree" value="1" <?=($this->originalInput('setfree', ($entry->getSetFree()))) ? 'checked="checked"' : '' ?> />
                <label for="setfree-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="setfree-no" name="setfree" value="0"  <?=(!$this->originalInput('setfree', ($entry->getSetFree()))) ? 'checked="checked"' : '' ?> />
                <label for="setfree-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group <?=$this->validation()->hasError('interpret') ? 'has-error' : '' ?>">
        <label for="interpret" class="col-lg-2 control-label">
            <?=$this->getTrans('interpret') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="interpret"
                   name="interpret"
                   value="<?=$this->escape($this->originalInput('interpret', ($entry->getInterpret()))) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('songtitel') ? 'has-error' : '' ?>">
        <label for="songtitel" class="col-lg-2 control-label">
            <?=$this->getTrans('songtitel') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="songtitel"
                   name="songtitel"
                   value="<?=$this->escape($this->originalInput('songtitel', ($entry->getSongTitel()))) ?>" />
        </div>
    </div>
    <?php if (!$this->getRequest()->getParam('suggestion') && $show_artwork) { ?>
    <div class="form-group <?=$this->validation()->hasError('artworkUrl') ? 'has-error' : '' ?>">
        <label for="artworkUrl" class="col-lg-2 control-label">
            <?=$this->getTrans('artworkUrl') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="artworkUrl"
                   name="artworkUrl"
                   value="<?=$this->escape($this->originalInput('artworkUrl', ($entry->getArtworkUrl()))) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('artworkUrl') ? 'has-error' : '' ?>">
        <label for="artworkUrl" class="col-lg-2 control-label">
            <a class="btn btn-large btn-success" id="searchiTunes"><?=$this->getTrans('artworkUrlget') ?> (iTunes)</a>
        </label>
        <img src="<?=$this->escape($this->originalInput('artworkUrl', ($entry->getArtworkUrl()))) ?>" name="artworkUrlimg" id="artworkUrlimg" class="img-thumbnail" alt="iTunes">
    </div>
    <?php } ?>
    <?=($entry->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
<?php if (!$this->getRequest()->getParam('suggestion') && $show_artwork) { ?>
<script>
  $(function () {
    $('#searchiTunes').on('click', function() {
        $.getJSON('<?=$this->getUrl(['action' => 'searchiTunes'], 'admin', true) ?>/term/'+$('#interpret').val()+' - '+$('#songtitel').val(), function(json) {
            $('#artworkUrl').val(json['URL']);
            
            $('#artworkUrlimg').attr("src", json['URL']);
        });
    });
    $('#artworkUrl').on('focusout', function() { 
        $('#artworkUrlimg').removeAttr("src").attr("src", $('#artworkUrl').val());
    });

  });
</script>
<?php } ?>
