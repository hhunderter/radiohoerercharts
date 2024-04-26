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
<form id="rhcForm" name="rhcForm" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('interpret') ? ' has-error' : '' ?>">
        <label for="interpret" class="col-xl-2 col-form-label">
            <?=$this->getTrans('interpret') ?>
        </label>
        <div class="col-xl-4">
            <input class="form-control"
                   type="text"
                   id="interpret"
                   name="interpret"
                   value="<?=$this->escape($this->originalInput('interpret')) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('songtitel') ? ' has-error' : '' ?>">
        <label for="songtitel" class="col-xl-2 col-form-label">
            <?=$this->getTrans('songtitel') ?>
        </label>
        <div class="col-xl-4">
            <input class="form-control"
                   type="text"
                   id="songtitel"
                   name="songtitel"
                   value="<?=$this->escape($this->originalInput('songtitel')) ?>" />
        </div>
    </div>

    <?php if ($captchaNeeded && $this->get('defaultcaptcha')) : ?>
        <?=$defaultcaptcha->getCaptcha($this) ?>
    <?php endif; ?>
    <div class="row mb-3">
        <div class="offset-xl-2 col-xl-8">
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
</form>
