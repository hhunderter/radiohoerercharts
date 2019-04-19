<?php $entrie = $this->get('entrie'); ?>
<h1><?=($entrie != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('datecreate') ?>
        </div>
        <div class="col-lg-4">
            <?php
            if($entrie != ''){
                $datenow = new \Ilch\Date($entrie->getDateCreate());
                echo $datenow->format('d.m.Y H:i');
            }else{
                echo $this->getTrans('new');
            }
            ?>
        </div>
    </div>
    <?php if (!$this->getRequest()->getParam('suggestion')) : ?>
    <div class="form-group <?=$this->validation()->hasError('setfree') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('setfree') ?>
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="setfree-on" name="setfree" value="1" 
                    <?php if ($entrie != '' AND $entrie->getSetFree() == 1): ?>
                        checked="checked"
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="setfree-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="setfree-off" name="setfree" value="0" 
                    <?php if ($entrie != '' AND $entrie->getSetFree() == 0): ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="setfree-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="form-group <?=$this->validation()->hasError('interpret') ? 'has-error' : '' ?>">
        <label for="interpret" class="col-lg-2 control-label">
            <?=$this->getTrans('interpret') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="interpret"
                   name="interpret"
                   value="<?php if ($entrie != '') { echo $this->escape($entrie->getInterpret()); } ?>" />
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
                   value="<?php if ($entrie != '') { echo $this->escape($entrie->getSongTitel()); } ?>" />
        </div>
    </div>

    <?=($entrie != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
