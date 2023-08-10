<?php

/** @var \Ilch\View $this */

/** @var bool $captchaNeeded */
$captchaNeeded = $this->get('captchaNeeded');

/** @var \Captcha\DefaultCaptcha $defaultcaptcha */
$defaultcaptcha = $this->get('defaultcaptcha');

/** @var \Captcha\GoogleCaptcha $googlecaptcha */
$googlecaptcha = $this->get('googlecaptcha');
?>
<h1><?=$this->getTrans('add') ?></h1>
<form id="rhcForm" name="rhcForm" class="form-horizontal" method="POST">
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
                   value="<?=$this->escape($this->originalInput('interpret')) ?>" />
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
                   value="<?=$this->escape($this->originalInput('songtitel')) ?>" />
        </div>
    </div>
    <?php if ($captchaNeeded) { ?>
        <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
            <label class="col-lg-2 control-label">
                <?=$this->getTrans('captcha') ?>
            </label>
            <div class="col-lg-8">
                <?=$this->getCaptchaField() ?>
            </div>
        </div>
        <?php if ($captchaNeeded && $defaultcaptcha) : ?>
            <?=$defaultcaptcha->getCaptcha($this) ?>
        <?php endif; ?>
        <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
            <div class="col-lg-offset-2 col-lg-8">
                <?php
                if ($captchaNeeded) {
                    if ($googlecaptcha) {
                        echo $googlecaptcha->setForm('rhcForm')->getCaptcha($this, 'addButton');
                    } else {
                        echo $this->getSaveBar('addButton');
                    }
                } else {
                    echo $this->getSaveBar('addButton');
                }
                ?>
            </div>
        </div>
    <?php } ?>
    <?=$this->getSaveBar('addButton') ?>
</form>
