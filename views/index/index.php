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
				<col/>
				<col/>
				<col/>
			</colgroup>
			<thead>
				<tr>
					<th align="center"><?=$this->getTrans('place') ?></th>
					<th align="center"><?=$this->getTrans('interpret') ?></th>
					<th align="center"><?=$this->getTrans('songtitel') ?></th>
					<th align="center"><?=$this->getTrans('vote') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->get('entries') as $entry): ?>
				<tr>
					<td align="center"><?=$platz ?></td>
					<td align="center"><?=$this->escape($entry->getInterpret()) ?></td>
					<td align="center"><?=$this->escape($entry->getSongTitel()) ?></td>
					<td align="center"><?=$this->get('hoererchartsMapper')->getStars($entry->getVotes(), $this->get('config')) ?></td>
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
					<col/>
					<col/>
				</colgroup>
				<thead>
					<tr>
						<th align="center">&nbsp;</th>
						<th align="center"><?=$this->getTrans('interpret') ?></th>
						<th align="center"><?=$this->getTrans('songtitel') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->get('entries') as $entry): ?>
					<tr>
						<td align="center"><input type="radio" name="hoerercharts-d" value="<?=$entry->getId() ?>"></td>
						<td align="center"><?=$this->escape($entry->getInterpret()) ?></td>
						<td align="center"><?=$this->escape($entry->getSongTitel()) ?></td>
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