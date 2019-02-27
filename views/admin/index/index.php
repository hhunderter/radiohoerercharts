<h1><?=$this->getTrans('manage') ?></h1>
<?=$this->get('votedatetime') ?> <a href="javascript:votedatetime()" title="Bearbeiten"><span class="fa fa-edit text-success"></span></a>
<br><br>
<?php if ($this->get('entries')): ?>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <br />
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
				<col class="icon_width">
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                    <th></th>
					<th></th>
					<th><?=$this->getTrans('interpret') ?></th>
					<th><?=$this->getTrans('songtitel') ?></th>
					<th><?=$this->getTrans('vote') ?></th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry): ?>
                <tbody>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $entry->getId()]) ?></td>
						<td><?=$this->getEditIcon(['action' => 'treat', 'id' => $entry->getId()]) ?></td>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
						<td><?=$this->escape($entry->getSongTitel()) ?></td>
						<td><?=$entry->getVotes() ?> (<?=$this->get('hoererchartsMapper')->getStars($entry->getVotes(), $this->get('config'), true) ?>)</td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
        </table>
    </div>
    <?php
    echo $this->getListBar(['delete' => 'delete']);
    ?>
</form>
<?php else: ?>
<div class="alert alert-danger">
	<?=$this->getTrans('noentriesadmin') ?>
</div>
<?php endif; ?>
<?=$this->getDialog('radiohoererchartsModal', $this->getTrans('settings'), '<iframe frameborder="0"></iframe>'); ?>
<script>
function votedatetime(){
	$('#radiohoererchartsModal').modal('show');
	var src = '<?=$this->getUrl(['controller' => 'settings', 'action' => 'votedatetime']) ?>';
	var height = '650px';
	var width = '100%';

	$('#radiohoererchartsModal iframe').attr({'src': src,
		'height': height,
		'width': width});
};
$(".btn-primary").on("click", function () {
  document.location.reload();
  //document.location.href = document.location.href;
});
$("#radiohoererchartsModal").on("hide.bs.modal", function () {
	document.location.reload();
});
function reload() {
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>
