<?php
$hoererchartsconfig = $this->get('config');
$userMapper = $this->get('userMapper');
?>
<h1>
    <?=$this->getTrans('hoerercharts').$this->get('gettext') ?>
    <?php if ($hoererchartsconfig['allowsuggestion'] and ((!$this->getUser() and $hoererchartsconfig['guestallow']) or $this->getUser())): ?>
    <div class="pull-right">
        <a href="<?=$this->getUrl(['action' => 'treat']); ?>"><?=$this->getTrans('suggestionnew') ?></a>
    </div>
    <?php endif; ?>
</h1>
<div class="teams" id="hoerercharts-container">
    <div class="col-lg-12" id="hoerercharts-form-container">
    <?=$this->getTrans('votetext', ((!$hoererchartsconfig['guestallow'])?$this->getTrans('votetextuser'):$this->getTrans('votetextguest')), $hoererchartsconfig['Program_Name']) ?>
    <?=((!$this->getUser() and !$hoererchartsconfig['guestallow'])?'':$this->getTrans('votetextvote')) ?><br><br>
    <?php if ($this->get('voted')): ?>
        <?php if (!$this->getUser() and !$hoererchartsconfig['guestallow']): ?>
        <?=$this->getTrans('nouser') ?>
        <?php if ($this->get('regist_accept') == '1'): ?>
        <br><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']); ?>">--<?=$this->getTrans('register'); ?>--</a>
        <?php endif; ?>
        <br><br>
        <?php endif; ?>
        <p class="<?=($this->get('vote_allowed')?'text-success':'text-danger') ?>"><?=$this->get('votedatetime') ?></p>
        <?php if ($this->get('entries')): ?>
        <?php
        $platz = 1;
        ?>
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="col-lg-4">
                <col class="col-lg-4">
                <col class="col-lg-3">
                <col class="icon_width">
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('place') ?></th>
                    <th><?=$this->getTrans('interpret') ?></th>
                    <th><?=$this->getTrans('songtitel') ?></th>
                    <th><?=$this->getTrans('vote') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry): ?>
                <tr>
                    <td><?=$platz ?></td>
                    <td><?=$this->escape($entry->getInterpret()) ?></td>
                    <td><?=$this->escape($entry->getSongTitel()) ?></td>
                    <td><?=$this->get('hoererchartsMapper')->getStars($entry->getVotes(), $hoererchartsconfig) ?></td>
                    <?php $User = $userMapper->getUserById($entry->getUser_Id()); ?>
                    <td><span class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User?$this->getTrans('guest'):$this->escape($User->getName())) ?>"></span></td>
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
        <p class="<?=($this->get('vote_allowed')?'text-success':'text-danger') ?>"><?=$this->get('votedatetime') ?></p>
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
                    <col class="col-lg-5">
                    <col class="icon_width">
                </colgroup>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?=$this->getTrans('interpret') ?></th>
                        <th><?=$this->getTrans('songtitel') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('entries') as $entry): ?>
                    <tr>
                        <td><input type="radio" name="hoerercharts-d" value="<?=$entry->getId() ?>"></td>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
                        <td><?=$this->escape($entry->getSongTitel()) ?></td>
                        <?php $User = $userMapper->getUserById($entry->getUser_Id()); ?>
                        <td><span class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User?$this->getTrans('guest'):$this->escape($User->getName())) ?>"></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="form-group">
                <div class="col-lg-8">
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
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>