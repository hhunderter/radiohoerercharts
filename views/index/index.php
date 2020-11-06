<?php
$hoererchartsconfig = $this->get('config');
$userMapper = $this->get('userMapper');
?>
<h1>
    <?=$hoererchartsconfig['Program_Name'].$this->get('gettext') ?>
    <?php if ($hoererchartsconfig['allowsuggestion'] and ((!$this->getUser() and $hoererchartsconfig['guestallow']) or $this->getUser())) {
    ?>
    <div class="pull-right">
        <a href="<?=$this->getUrl(['action' => 'treat']); ?>"><?=$this->getTrans('suggestionnew') ?></a>
    </div>
    <?php
} ?>
</h1>
<div class="teams" id="hoerercharts-container">
    <div class="col-lg-12" id="hoerercharts-form-container">
    <?=nl2br($this->getHtmlFromBBCode($this->escape($this->get('hoererchartsMapper')->getvotetext()))) ?>
    <?=((!$this->getUser() and !$hoererchartsconfig['guestallow'])?'':$this->getTrans('votetextvote')) ?><br><br>
    <?php if ($this->get('voted')) {
        ?>
        <?php if (!$this->getUser() and !$hoererchartsconfig['guestallow']) {
            ?>
        <?=$this->getTrans('nouser', $hoererchartsconfig['Program_Name']) ?>
        <?php if ($this->get('regist_accept') == '1') {
                ?>
        <br><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']); ?>">--<?=$this->getTrans('register'); ?>--</a>
        <?php
            } ?>
        <br><br>
        <?php
        } ?>
        <p class="<?=($this->get('vote_allowed')?'text-success':'text-danger') ?>"><?=$this->get('votedatetime') ?></p>
        <?php if ($this->get('entries')) {
            ?>
        <?php
        $platz = 1; ?>
        <table class="table table-hover table-striped">
            <colgroup>
                <?php if ($this->get('show_sortedlist')) {
                ?>
                <col class="icon_width">
                <?php
                } ?>
                <?php if ($hoererchartsconfig['show_artwork']) {
                ?>
                <col class="col-lg-1">
                <?php
                } ?>
                <col class="col-lg-4">
                <col class="col-lg-4">
                <col class="col-lg-2">
                <col class="icon_width">
            </colgroup>
            <thead>
                <tr>
                    <?php if ($this->get('show_sortedlist')) {
                    ?>
                    <th><?=$this->getTrans('place') ?></th>
                    <?php
                    } ?>
                    <?php if ($hoererchartsconfig['show_artwork']) {
                        ?>
                    <th><?=$this->getTrans('artwork') ?></th>
                    <?php
                    } ?>
                    <th><?=$this->getTrans('interpret') ?></th>
                    <th><?=$this->getTrans('songtitel') ?></th>
                    <th><?=$this->getTrans('vote') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry) {
            ?>
                <tr>
                    <?php if ($this->get('show_sortedlist')) {
                    ?>
                    <td><?=$platz ?></td>
                    <?php
                    } ?>
                    <?php if ($hoererchartsconfig['show_artwork']) {
                    ?>
                    <td>
                        <?php if ($entry->getArtworkUrl()) {
                        ?>
                        <img src="<?=$this->escape($entry->getArtworkUrl()) ?>" class="img-thumbnail">
                        <?php
                        } ?>
                    </td>
                    <?php
                    } ?>
                    <td><?=$this->escape($entry->getInterpret()) ?></td>
                    <td><?=$this->escape($entry->getSongTitel()) ?></td>
                    <td><?=$this->get('hoererchartsMapper')->getStars($entry->getVotes()) ?></td>
                    <?php $User = $userMapper->getUserById($entry->getUser_Id()); ?>
                    <td><span class="fas fa-info-circle text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User?$this->getTrans('guest'):$this->escape($User->getName())) ?>"></span></td>
                </tr>
                <?php
                $platz++; ?>
                <?php
        } ?>
            </tbody>
        </table>
        <?php
        } else {
            ?>
        <div class="alert alert-danger">
        <?=$this->getTrans('noentries') ?>
        </div>
        <?php
        } ?>
    <?php
    } else {
        ?>
        <p class="<?=($this->get('vote_allowed')?'text-success':'text-danger') ?>"><?=$this->get('votedatetime') ?></p>
        <?php if ($this->validation()->hasError('hoerercharts-d')) {
            ?>
        <div class="alert alert-danger">
        <?=$this->getTrans('voteerror') ?>
        </div>
        <?php
        } ?>
        <?php if ($this->get('entries')) {
            ?>
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
                    <?php foreach ($this->get('entries') as $entry) {
                ?>
                    <tr>
                        <td><label><input type="radio" name="hoerercharts-d" value="<?=$entry->getId() ?>"></label></td>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
                        <td><?=$this->escape($entry->getSongTitel()) ?></td>
                        <?php $User = $userMapper->getUserById($entry->getUser_Id()); ?>
                        <td><span class="fas fa-info-circle text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User?$this->getTrans('guest'):$this->escape($User->getName())) ?>"></span></td>
                    </tr>
                    <?php
            } ?>
                </tbody>
            </table>

            <div class="form-group">
                <div class="col-lg-8">
                    <?=$this->getSaveBar('addButton', 'HoererCharts') ?>
                </div>
            </div>
        </form>
        <?php
        } else {
            ?>
        <div class="alert alert-danger">
        <?=$this->getTrans('noentries') ?>
        </div>
        <?php
        } ?>
    <?php
    } ?>
    </div>
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>