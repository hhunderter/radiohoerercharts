<h1><?=$this->getTrans('menuSettings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
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
                   value="<?=$this->get('Program_Name') ?>"
                   required />
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
                   value="<?=$this->get('allsecvote') ?>"
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
                   value="<?=$this->get('program_secduration') ?>"
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
                <input type="radio" class="flipswitch-input" id="guestallow-on" name="guestallow" value="1" <?php if ($this->get('guestallow') == '1') { echo 'checked="checked"'; } ?> />
                <label for="guestallow-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="guestallow-off" name="guestallow" value="0" <?php if ($this->get('guestallow') != '1') { echo 'checked="checked"'; } ?> />
                <label for="guestallow-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
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
                <input type="radio" class="flipswitch-input" id="showstars-on" name="showstars" value="1" <?php if ($this->get('showstars') == '1') { echo 'checked="checked"'; } ?> />
                <label for="showstars-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="showstars-off" name="showstars" value="0" <?php if ($this->get('showstars') != '1') { echo 'checked="checked"'; } ?> />
                <label for="showstars-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
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
                   value="<?=$this->get('Star1') ?>"
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
                   value="<?=$this->get('Star2') ?>"
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
                   value="<?=$this->get('Star3') ?>"
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
                   value="<?=$this->get('Star4') ?>"
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
                   value="<?=$this->get('Star5') ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
