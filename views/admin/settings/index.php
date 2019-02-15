<h1><?=$this->getTrans('menuSettings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
	<h1><?=$this->getTrans('showstars') ?></h1>
	<div class="form-group <?=$this->validation()->hasError('showstars') ? 'has-error' : '' ?>">
        <label for="showstars" class="col-lg-2 control-label">
            <?=$this->getTrans('showstarsText') ?>:
        </label>
        <div class="col-lg-1">
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
        <div class="col-lg-1">
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
        <div class="col-lg-1">
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
        <div class="col-lg-1">
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
        <div class="col-lg-1">
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
        <div class="col-lg-1">
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
