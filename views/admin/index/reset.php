<h1><?=$this->getTrans('reset') ?></h1>
<div class="form-group">
	<a href="<?=$this->getUrl(['action' => 'reset', 'reset' => true], null, true) ?>" class="btn btn-danger btn-lg active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('reset') ?></a>
</div>
