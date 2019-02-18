<h1>
    <?=$this->getTrans('hoerercharts').$this->get('gettext') ?>
</h1>
<div class="teams" id="hoerercharts-container">
	<div class="col-lg-12" id="hoerercharts-form-container">
	<?php if ($this->get('voted')): ?>
		<?php if (!$this->getUser()): ?>
		<?=$this->getTrans('nouser') ?>
		<?php if ($this->get('regist_accept') == '1'): ?>
        <br><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']); ?>">--<?=$this->getTrans('register'); ?>--</a>
		<?php endif; ?>
		<br><br>
		<?php endif; ?>
		<?php if ($this->get('entries')): ?>
		<?php
		$platz = 1;
		?>
		<table class="table table-hover table-striped">
			<colgroup>
				<col class="icon_width">
				<col class="col-lg-4">
				<col class="col-lg-4">
				<col class="col-lg-4">
			</colgroup>
			<thead>
				<tr>
					<th><?=$this->getTrans('place') ?></th>
					<th><?=$this->getTrans('interpret') ?></th>
					<th><?=$this->getTrans('songtitel') ?></th>
					<th><?=$this->getTrans('vote') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->get('entries') as $entry): ?>
				<tr>
					<td><?=$platz ?></td>
					<td><?=$this->escape($entry->getInterpret()) ?></td>
					<td><?=$this->escape($entry->getSongTitel()) ?></td>
					<td><?=$this->get('hoererchartsMapper')->getStars($entry->getVotes(), $this->get('config')) ?></td>
				</tr>
				<?php
				$platz++;
				?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php else: ?>
		<div class="alert alert-danger">
		<?=$this->getTrans('noentries') ?>
		</div>
		<?php endif; ?>
	<?php else: ?>
		<?=$this->getTrans('votetext') ?>
		<br><br>
		<?php if ($this->validation()->hasError('hoerercharts-d')): ?>
		<div class="alert alert-danger">
		<?=$this->getTrans('voteerror') ?>
		</div>
		<?php endif; ?>
		<?php if ($this->get('entries')): ?>
		<form class="form-horizontal" method="POST">
		<?=$this->getTokenField() ?>
			<table class="table table-hover table-striped">
				<colgroup>
					<col class="icon_width">
					<col class="col-lg-6">
					<col class="col-lg-6">
				</colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th><?=$this->getTrans('interpret') ?></th>
						<th><?=$this->getTrans('songtitel') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->get('entries') as $entry): ?>
					<tr>
						<td><input type="radio" name="hoerercharts-d" value="<?=$entry->getId() ?>"></td>
						<td><?=$this->escape($entry->getInterpret()) ?></td>
						<td><?=$this->escape($entry->getSongTitel()) ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-8">
					<?=$this->getSaveBar('addButton', 'HoererCharts') ?>
				</div>
			</div>
		</form>
		<?php else: ?>
		<div class="alert alert-danger">
		<?=$this->getTrans('noentries') ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
</div>