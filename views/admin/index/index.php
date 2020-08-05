<h1><?=$this->getTrans('manage') ?></h1>
<?php $config = $this->get('config'); ?>
<div class="form-group">
<?php if (!$this->get('suggestion')): ?>

    <div class="col-lg-6">
        <?=$this->get('votedatetime') ?> <a href="javascript:votedatetime()" title="<?=$this->getTrans('edit') ?>"><span class="fa fa-edit text-success"></span></a>
    </div>
<?php else: ?>
    <div class="col-lg-6">
        <div class="flipswitch">
            <input type="radio" class="flipswitch-input" id="allowsuggestion-on" name="allowsuggestion" value="1" 
                <?php if ($config != '' AND $config['allowsuggestion'] == 1): ?>
                    checked="checked"
                <?php else: ?>
                    checked="checked"
                <?php endif; ?> />
            <label for="allowsuggestion-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
            <input type="radio" class="flipswitch-input" id="allowsuggestion-off" name="allowsuggestion" value="0" 
                <?php if ($config != '' AND $config['allowsuggestion'] == 0): ?>
                    checked="checked"
                <?php endif; ?> />
            <label for="allowsuggestion-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
            <span class="flipswitch-selection"></span>
        </div>
    </div>
    <div id="console-event"></div>
<?php endif; ?>
</div>
<div class="form-group">
    <ul class="nav nav-tabs">
        <li class="<?=(!$this->get('suggestion')?'active':'') ?>">
            <a href="<?=$this->getUrl(['action' => 'index']) ?>"><?=$this->getTrans('index') ?></a>
        </li>
        <li class="<?=($this->get('suggestion')?'active':'') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'suggestion' => 'true']) ?>"><?=$this->getTrans('suggestion') ?> <span class="badge"><?=$this->get('badgeSuggestion') ?></span></a>
        </li>
    </ul>
</div>
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
                <col class="icon_width">
                <col>
                <col>
                <?php if (!$this->get('suggestion')): ?><col><?php endif; ?>
                <col>
                <col>
            </colgroup>
            <?php
                $urladd = array('order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc');
                if ($this->get('suggestion')) $urladd['suggestion'] = 'true';
            ?>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'interpret'], $urladd)) ?>" title="<?=$this->getTrans('interpret') ?>">
                        <?=$this->getTrans('interpret') ?>
                        <i class="fa fa-sort<?php echo $this->get('sort_column') == 'interpret' ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->get('sort_order')) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'songtitel'], $urladd)) ?>" title="<?=$this->getTrans('songtitel') ?>">
                        <?=$this->getTrans('songtitel') ?>
                        <i class="fa fa-sort<?php echo $this->get('sort_column') == 'songtitel' ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->get('sort_order')) : ''; ?>"></i>
                        </a>
                    </th>
                    <?php if (!$this->get('suggestion')): ?>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'votes'], $urladd)) ?>" title="<?=$this->getTrans('vote') ?>">
                        <?=$this->getTrans('vote') ?>
                        <i class="fa fa-sort<?php echo $this->get('sort_column') == 'votes' ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->get('sort_order')) : ''; ?>"></i>
                        </a>
                    </th>
                    <?php endif; ?>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'datecreate'], $urladd)) ?>" title="<?=$this->getTrans('datecreate') ?>">
                        <?=$this->getTrans('datecreate') ?>
                        <i class="fa fa-sort<?php echo $this->get('sort_column') == 'datecreate' ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->get('sort_order')) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'user'], $urladd)) ?>" title="<?=$this->getTrans('user') ?>">
                        <?=$this->getTrans('user') ?>
                        <i class="fa fa-sort<?php echo $this->get('sort_column') == 'user' ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->get('sort_order')) : ''; ?>"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry): ?>
                <tbody>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                        <td><?=$this->getDeleteIcon(array_merge(['action' => 'del', 'id' => $entry->getId()],(($this->get('suggestion'))?['suggestion' => 'true']:[]))) ?></td>
                        <td><?=$this->getEditIcon(array_merge(['action' => 'treat', 'id' => $entry->getId()],(($this->get('suggestion'))?['suggestion' => 'true']:[]))) ?></td>
                        <td>
                        <?php if (!$this->get('suggestion')): ?>
                            <?php if ($entry->getSetFree() == 1): ?>
                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $entry->getId(), 'status_man' => -1], null, true) ?>">
                                    <span class="fa fa-check-square-o text-info"></span>
                                </a>
                            <?php else: ?>
                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $entry->getId(), 'status_man' => -1], null, true) ?>">
                                    <span class="fa fa-square-o text-info"></span>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?=$this->getUrl(['action' => 'suggestionenable', 'id' => $entry->getId()], null, true) ?>">
                                <span class="fa fa-reply text-info"></span>
                            </a>
                        <?php endif; ?>
                        </td>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
                        <td><?=$this->escape($entry->getSongTitel()) ?></td>
                        <?php if (!$this->get('suggestion')): ?><td><?=$entry->getVotes() ?> (<?=$this->get('hoererchartsMapper')->getStars($entry->getVotes(), $config, true) ?>)</td><?php endif; ?>
                        <td>
                        <?php
                            $datenow = new \Ilch\Date($entry->getDateCreate());
                            echo $datenow->format('d.m.Y H:i');
                        ?>
                        </td>
                        <td>
                        <?php
                            $userMapper = new \Modules\User\Mappers\User();
                            
                            $user_id = $entry->getUser_Id();
                            $user = $userMapper->getUserById($user_id);
                            if ($user)
                                echo $this->escape($user->getName());
                            else
                                echo $this->getTrans('guest');
                        ?>
                        </td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
        </table>
    </div>
    <?php
    echo $this->getListBar(array_merge((($this->get('suggestion'))?['setfree' => 'suggestionenable']:[]),['delete' => 'delete']));
    ?>
</form>
<?php else: ?>
<div class="alert alert-danger">
    <?=$this->getTrans('noentriesadmin') ?>
</div>
<?php endif; ?>
<?php if (!$this->get('suggestion')): ?>
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
<?php else: ?>
<script>
  $(function() {
    $('#allowsuggestion-on').change(function() {
      $('#console-event').html('Toggle: ' + 'true')
      window.open("<?=$this->getUrl(['action' => 'allowsuggestion']) ?>","_self")
    })
    $('#allowsuggestion-off').change(function() {
      window.open("<?=$this->getUrl(['action' => 'allowsuggestion']) ?>","_self")
    })
  })
</script>
<?php endif; ?>