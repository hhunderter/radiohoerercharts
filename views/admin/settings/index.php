<h1><?=$this->getTrans('menuSettings') ?></h1>
<div class="form-group">
    <ul class="nav nav-tabs">
        <li class="<?=(!$this->get('settings_language')?'active':'') ?>">
            <a href="<?=$this->getUrl(['action' => 'index']) ?>"><?=$this->getTrans('index') ?></a>
        </li>
        <li class="<?=($this->get('settings_language')?'active':'') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'settings_language' => 'true']) ?>"><?=$this->getTrans('language') ?></a>
        </li>
    </ul>
</div>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(($this->get('settings_language')?['action' => $this->getRequest()->getActionName(), 'settings_language' => 'true']:['action' => $this->getRequest()->getActionName()])) ?>">
    <?=$this->getTokenField() ?>
    <?php if (!$this->get('settings_language')) {
    ?>
    <h1><?=$this->getTrans('Program_Name') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Program_Name') ? 'has-error' : '' ?>">
        <label for="Program_Name" class="col-lg-2 control-label">
            <?=$this->getTrans('Program_NameText') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="Program_Name"
                   name="Program_Name"
                   value="<?=$this->escape($this->originalInput('Program_Name', $this->get('Program_Name'))) ?>"
                   placeholder="<?=$this->getTrans('Program_Name') ?>"
                    />
        </div>
    </div>
    <h1><?=$this->getTrans('Allsecvote') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('allsecvote') ? 'has-error' : '' ?>">
        <label for="allsecvote" class="col-lg-2 control-label">
            <?=$this->getTrans('AllsecvoteText') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="allsecvote"
                   name="allsecvote"
                   min="0"
                   value="<?=$this->escape($this->originalInput('allsecvote', $this->get('allsecvote'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('Program_secduration') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('program_secduration') ? 'has-error' : '' ?>">
        <label for="program_secduration" class="col-lg-2 control-label">
            <?=$this->getTrans('Program_secdurationText') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="program_secduration"
                   name="program_secduration"
                   min="0"
                   value="<?=$this->escape($this->originalInput('program_secduration', $this->get('program_secduration'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('guestallow') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('guestallow') ? 'has-error' : '' ?>">
        <label for="guestallow" class="col-lg-2 control-label">
            <?=$this->getTrans('guestallowText') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="guestallow-yes" name="guestallow" value="1" <?=($this->originalInput('guestallow', $this->get('guestallow')))?'checked="checked"':'' ?> />
                <label for="guestallow-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="guestallow-no" name="guestallow" value="0"  <?=(!$this->originalInput('guestallow', $this->get('guestallow')))?'checked="checked"':'' ?> />  
                <label for="guestallow-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    
    <h1><?=$this->getTrans('show_artwork') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('show_artwork') ? 'has-error' : '' ?>">
        <label for="show_artwork" class="col-lg-2 control-label">
            <?=$this->getTrans('show_artworkText') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="show_artwork-yes" name="show_artwork" value="1" <?=($this->originalInput('show_artwork', $this->get('show_artwork')))?'checked="checked"':'' ?> />
                <label for="show_artwork-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="show_artwork-no" name="show_artwork" value="0"  <?=(!$this->originalInput('show_artwork', $this->get('show_artwork')))?'checked="checked"':'' ?> />  
                <label for="show_artwork-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h1><?=$this->getTrans('showstars') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('showstars') ? 'has-error' : '' ?>">
        <label for="showstars" class="col-lg-2 control-label">
            <?=$this->getTrans('showstarsText') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="showstars-yes" name="showstars" value="1" <?=($this->originalInput('showstars', $this->get('showstars')))?'checked="checked"':'' ?> />
                <label for="showstars-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="showstars-no" name="showstars" value="0"  <?=(!$this->originalInput('showstars', $this->get('showstars')))?'checked="checked"':'' ?> />  
                <label for="showstars-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <h1><?=$this->getTrans('Star1') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Star1') ? 'has-error' : '' ?>">
        <label for="Star1" class="col-lg-2 control-label">
            <?=$this->getTrans('Star1Text') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Star1"
                   name="Star1"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Star1', $this->get('Star1'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('Star2') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Star2') ? 'has-error' : '' ?>">
        <label for="Star2" class="col-lg-2 control-label">
            <?=$this->getTrans('Star2Text') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Star2"
                   name="Star2"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Star2', $this->get('Star2'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('Star3') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Star3') ? 'has-error' : '' ?>">
        <label for="Star3" class="col-lg-2 control-label">
            <?=$this->getTrans('Star3Text') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Star3"
                   name="Star3"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Star3', $this->get('Star3'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('Star4') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Star4') ? 'has-error' : '' ?>">
        <label for="Star4" class="col-lg-2 control-label">
            <?=$this->getTrans('Star4Text') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Star4"
                   name="Star4"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Star4', $this->get('Star4'))) ?>"
                   required />
        </div>
    </div>
    <h1><?=$this->getTrans('Star5') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('Star5') ? 'has-error' : '' ?>">
        <label for="Star5" class="col-lg-2 control-label">
            <?=$this->getTrans('Star5Text') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Star5"
                   name="Star5"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Star5', $this->get('Star5'))) ?>"
                   required />
        </div>
    </div>
    <?php
} else {
        ?>
    <div class="form-row">
        <div class="form-group col-lg-6 <?=($this->validation()->hasError('votetext_de') || $this->validation()->hasError('votetext_en')) ? 'has-error' : '' ?>">
            <label for="votetext_de"><?=$this->getTrans('german') ?></label>
            <textarea class="form-control ckeditor"
                      id="votetext_de"
                      name="votetext_de"
                      toolbar="ilch_bbcode"
                      required><?=$this->escape($this->originalInput('votetext_de', $this->get('votetext_de'))) ?></textarea>
        </div>
        <div class="form-group col-lg-6">
            <label for="votetext_en"><?=$this->getTrans('english') ?></label>
            <textarea class="form-control ckeditor"
                      id="votetext_en"
                      name="votetext_en"
                      toolbar="ilch_bbcode"
                      required><?=$this->escape($this->originalInput('votetext_en', $this->get('votetext_en'))) ?></textarea>
        </div>
    </div>
    <h1><?=$this->getTrans('language_footer', $this->getTrans('votetextguest'), $this->getTrans('votetextuser'), $this->get('Program_Name')) ?></h1>
    <?php
    } ?>
    <?=$this->getSaveBar() ?>
</form>
