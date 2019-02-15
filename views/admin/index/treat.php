<?php $entrie = $this->get('entrie'); ?>
<h1><?=($entrie != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
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
