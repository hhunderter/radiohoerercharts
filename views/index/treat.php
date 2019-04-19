<h1><?=$this->getTrans('add') ?></h1>
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
                   value="<?php if ($this->getRequest()->getPost('interpret') != '') { echo $this->escape($this->getRequest()->getPost('interpret')); } ?>" />
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
                   value="<?php if ($this->getRequest()->getPost('songtitel') != '') { echo $this->escape($this->getRequest()->getPost('songtitel')); } ?>" />
        </div>
    </div>
    
    <?php if ($this->get('captchaNeeded')) : ?>
        <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
            <label class="col-lg-2 control-label">
                <?=$this->getTrans('captcha') ?>
            </label>
            <div class="col-lg-8">
                <?=$this->getCaptchaField() ?>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
            <div class="col-lg-offset-2 col-lg-3 input-group captcha">
                <input type="text"
                       class="form-control"
                       id="captcha-form"
                       name="captcha"
                       autocomplete="off"
                       placeholder="<?=$this->getTrans('captcha') ?>" />
                <span class="input-group-addon">
                    <a href="javascript:void(0)" onclick="
                            document.getElementById('captcha').src='<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                            document.getElementById('captcha-form').focus();"
                       id="change-image">
                        <i class="fa fa-refresh"></i>
                    </a>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <?=$this->getSaveBar('addButton') ?>
</form>
