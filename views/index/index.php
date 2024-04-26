<?php

/** @var \Ilch\View $this */

/** @var bool $voted */
$voted = $this->get('voted');

/** @var bool $show_sortedlist */
$show_sortedlist = $this->get('show_sortedlist');

/** @var bool $regist_accept */
$regist_accept = $this->get('regist_accept');

/** @var bool $vote_allowed */
$vote_allowed = $this->get('vote_allowed');

/** @var string $votedatetime */
$votedatetime = $this->get('votedatetime');

/** @var array $hoererchartsconfig */
$hoererchartsconfig = $this->get('config');

/** @var Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');

/** @var Modules\RadioHoererCharts\Mappers\HoererCharts $hoererchartsMapper */
$hoererchartsMapper = $this->get('hoererchartsMapper');

/** @var Modules\RadioHoererCharts\Models\HoererCharts[]|null $entries */
$entries = $this->get('entries');
?>
<h1>
    <?=$hoererchartsconfig['Program_Name'] . $this->get('gettext') ?>
    <?php if ($hoererchartsconfig['allowsuggestion'] && ((!$this->getUser() && $hoererchartsconfig['guestallow']) || $this->getUser())) { ?>
    <div class="float-end">
        <a href="<?=$this->getUrl(['action' => 'treat']); ?>"><?=$this->getTrans('suggestionnew') ?></a>
    </div>
    <?php } ?>
</h1>
<div class="teams" id="hoerercharts-container">
    <div class="col-xl-12" id="hoerercharts-form-container">
    <?=$this->purify($hoererchartsMapper->getVoteText()) ?>
    <?=((!$this->getUser() && !$hoererchartsconfig['guestallow']) ? '' : $this->getTrans('votetextvote')) ?><br><br>
    <?php if ($voted) { ?>
        <?php if (!$this->getUser() && !$hoererchartsconfig['guestallow']) { ?>
            <?=$this->getTrans('nouser', $hoererchartsconfig['Program_Name']) ?>
            <?php if ($regist_accept) { ?>
        <br><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']); ?>">--<?=$this->getTrans('register'); ?>--</a>
            <?php } ?>
        <br><br>
        <?php } ?>
        <p class="<?=($vote_allowed ? 'text-success' : 'text-danger') ?>"><?=$votedatetime ?></p>
        <?php if ($entries) { ?>
            <?php
            $platz = 1;
            ?>
        <table class="table table-hover table-striped">
            <colgroup>
                <?php if ($show_sortedlist) { ?>
                <col class="icon_width">
                <?php } ?>
                <?php if ($hoererchartsconfig['show_artwork']) { ?>
                <col class="col-xl-1">
                <?php } ?>
                <col class="col-xl-4">
                <col class="col-xl-4">
                <col class="col-xl-2">
                <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                <col class="icon_width">
                <?php } ?>
            </colgroup>
            <thead>
                <tr>
                    <?php if ($show_sortedlist) { ?>
                    <th><?=$this->getTrans('place') ?></th>
                    <?php } ?>
                    <?php if ($hoererchartsconfig['show_artwork']) { ?>
                    <th><?=$this->getTrans('artwork') ?></th>
                    <?php } ?>
                    <th><?=$this->getTrans('interpret') ?></th>
                    <th><?=$this->getTrans('songtitel') ?></th>
                    <th><?=$this->getTrans('vote') ?></th>
                    <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                    <th></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry) { ?>
                <tr>
                    <?php if ($show_sortedlist) { ?>
                    <td><?=$platz ?></td>
                    <?php } ?>
                    <?php if ($hoererchartsconfig['show_artwork']) { ?>
                    <td>
                        <?php if ($entry->getArtworkUrl()) { ?>
                        <img src="<?=$this->escape($entry->getArtworkUrl()) ?>" class="img-thumbnail" alt="<?=$this->getTrans('artwork') ?>">
                        <?php } ?>
                    </td>
                        <?php
                    } ?>
                    <td><?=$this->escape($entry->getInterpret()) ?></td>
                    <td><?=$this->escape($entry->getSongTitel()) ?></td>
                    <td><?=$hoererchartsMapper->getStars($entry->getVotes()) ?></td>
                    <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                        <?php $User = $userMapper->getUserById($entry->getUserId()); ?>
                    <td><span class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User ? $this->getTrans('guest') : $this->escape($User->getName())) ?>"></span></td>
                    <?php } ?>
                </tr>
                    <?php
                    $platz++;
                    ?>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="alert alert-danger">
            <?=$this->getTrans('noentries') ?>
        </div>
        <?php } ?>
    <?php } else { ?>
        <p class="<?=($vote_allowed ? 'text-success' : 'text-danger') ?>"><?=$votedatetime ?></p>
        <?php if ($this->validation()->hasError('hoerercharts-d')) { ?>
        <div class="alert alert-danger">
            <?=$this->getTrans('voteerror') ?>
        </div>
        <?php } ?>
        <?php if ($entries) { ?>
        <form method="POST">
            <?=$this->getTokenField() ?>
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <?php if ($hoererchartsconfig['show_artwork']) { ?>
                    <col class="col-xl-1">
                    <?php } ?>
                    <col class="col-xl-6">
                    <col class="col-xl-5">
                    <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                    <col class="icon_width">
                    <?php } ?>
                </colgroup>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <?php if ($hoererchartsconfig['show_artwork']) { ?>
                        <th><?=$this->getTrans('artwork') ?></th>
                        <?php } ?>
                        <th><?=$this->getTrans('interpret') ?></th>
                        <th><?=$this->getTrans('songtitel') ?></th>
                        <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                        <th></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry) { ?>
                    <tr>
                        <td><label><input type="radio" name="hoerercharts-d" value="<?=$entry->getId() ?>"></label></td>
                        <?php if ($hoererchartsconfig['show_artwork']) { ?>
                        <td>
                            <?php if ($entry->getArtworkUrl()) { ?>
                            <img src="<?=$this->escape($entry->getArtworkUrl()) ?>" class="img-thumbnail" alt="<?=$this->getTrans('artwork') ?>">
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
                        <td><?=$this->escape($entry->getSongTitel()) ?></td>
                        <?php if ($hoererchartsconfig['show_registered_by']) { ?>
                            <?php $User = $userMapper->getUserById($entry->getUserId()); ?>
                        <td><span class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-placement="left" title="<?=$this->getTrans('registered_by') ?>: <?=(!$User ? $this->getTrans('guest') : $this->escape($User->getName())) ?>"></span></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="row mb-3">
                <div class="col-xl-8">
                    <?=$this->getSaveBar('addButton', 'HoererCharts') ?>
                </div>
            </div>
        </form>
        <?php } else { ?>
        <div class="alert alert-danger">
            <?=$this->getTrans('noentries') ?>
        </div>
        <?php } ?>
    <?php } ?>
    </div>
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>
