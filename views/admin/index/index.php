<?php

/** @var \Ilch\View $this */

/** @var Modules\RadioHoererCharts\Mappers\HoererCharts $hoererchartsMapper */
$hoererchartsMapper = $this->get('hoererchartsMapper');

/** @var Modules\RadioHoererCharts\Mappers\HoererChartsList $hoererchartslistMapper */
$hoererchartslistMapper = $this->get('hoererchartslistMapper');

/** @var string $sort_order */
$sort_order = $this->get('sort_order');

/** @var string $sort_column */
$sort_column = $this->get('sort_column');

/** @var bool $suggestion */
$suggestion = $this->get('suggestion');

/** @var bool $allowsuggestion */
$allowsuggestion = $this->get('allowsuggestion');

/** @var bool $badgeSuggestion */
$badgeSuggestion = $this->get('badgeSuggestion');

/** @var int $list */
$list = $this->get('list');

/** @var int $activelist */
$activelist = $this->get('activelist');

/** @var int $filterlist */
$filterlist = $this->get('filterlist');

/** @var string $votedatetime */
$votedatetime = $this->get('votedatetime');

/** @var Modules\RadioHoererCharts\Models\HoererCharts[] $entries */
$entries = $this->get('entries');

/** @var Modules\RadioHoererCharts\Models\HoererChartsList[] $listentries */
$listentries = $this->get('listentries');


?>
<h1><?=$this->getTrans('manage') ?></h1>

<div class="form-group">
<?php if (!$suggestion && !$list) { ?>
    <div class="col-lg-6">
        <?=$this->getTrans('votedatetime') ?><?=$votedatetime ?> <a href="javascript:votedatetime()" title="<?=$this->getTrans('edit') ?>"><span class="fa-solid fa-pen-to-square text-success"></span></a>
    </div>
    <div class="form-group">
        <label for="filterlist" class="col-lg-2 control-label">
            <?=$this->getTrans('filter') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control" id="filterlist" name="filterlist" data-placeholder="<?=$this->getTrans('selectactive_list') ?>">
                    <option value=""<?=(!$filterlist) ? ' selected' : '' ?>></option>
                    <option value="1"<?=($filterlist == 1) ? ' selected' : '' ?>><?=$this->getTrans('list') ?> 1</option>
                    <option value="2"<?=($filterlist == 2) ? ' selected' : '' ?>><?=$this->getTrans('list') ?> 2</option>
           </select>
        </div>
    </div>
<?php } elseif (!$list) { ?>
    <div class="col-lg-6">
        <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="allowsuggestion-on" name="allowsuggestion" value="1" <?=($allowsuggestion) ? 'checked="checked"' : '' ?> />
                <label for="allowsuggestion-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="allowsuggestion-off" name="allowsuggestion" value="0"  <?=(!$allowsuggestion) ? 'checked="checked"' : '' ?> />
                <label for="allowsuggestion-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
    </div>
    <div id="console-event"></div>
<?php } else { ?>
<div class="col-lg-6">
    <div class="form-group">
        <label for="activelist" class="col-lg-2 control-label">
    <?=$this->getTrans('active_list') ?>
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control" id="activelist" name="activelist" data-placeholder="<?=$this->getTrans('selectactive_list') ?>">
                    <option value="1"<?=($activelist == 1) ? ' selected' : '' ?>><?=$this->getTrans('list') ?> 1</option>
                    <option value="2"<?=($activelist == 2) ? ' selected' : '' ?>><?=$this->getTrans('list') ?> 2</option>
           </select>
        </div>
    </div>
</div>
<?php } ?>
</div>
<div class="form-group">
    <ul class="nav nav-tabs">
        <li class="<?=(!$suggestion && !$list ? 'active' : '') ?>">
            <a href="<?=$this->getUrl(['action' => 'index']) ?>"><?=$this->getTrans('index') ?></a>
        </li>
        <li class="<?=($list ? 'active' : '') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'list' => $activelist]) ?>"><?=$this->getTrans('list') ?></a>
        </li>
        <li class="<?=($suggestion ? 'active' : '') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'suggestion' => 'true']) ?>"><?=$this->getTrans('suggestion') ?> <span class="badge"><?=$badgeSuggestion ?></span></a>
        </li>
    </ul>
</div>
<?php if ($list) { ?>
<div class="form-group">
    <ul class="nav nav-tabs">
        <li class="<?=($list == 1 ? 'active' : '') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'list' => '1']) ?>"><?=$this->getTrans('list') ?> 1</a>
        </li>
        <li class="<?=($list == 2 ? 'active' : '') ?>">
            <a href="<?=$this->getUrl(['action' => 'index', 'list' => '2']) ?>"><?=$this->getTrans('list') ?> 2</a>
        </li>
    </ul>
</div>
<?php } ?>
<form class="form-horizontal" method="POST" id="groupForm" action="<?=$this->getUrl(array_merge(['action' => $this->getRequest()->getActionName()], ($list ? ['list' => $list] : []), ($suggestion ? ['suggestion' => 'true'] : []))) ?>">
    <?=$this->getTokenField() ?>
    <br />
<?php if ($list) { ?>
    <div class="form-group">
        <table class="table table-borderless">
            <colgroup><col class="col-lg-6">
                <col class="col-lg-6">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th class="th-lg"><?=$this->getTrans('active') ?></th>
                    <th class="th-lg"><?=$this->getTrans('index') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                    <div class="listentries_list" id="listentries_list">
                        <ol id="listentries" class="sortable connectedSortable">
                        <?php
                        $listentrie = []; ?>
                        <?php foreach ($listentries as $entry) {
                            $listentrie[] = $entry->getHId(); ?>
                            <li class="handle_li" value="<?=$entry->getHId() ?>"><div><span class="fa-solid fa-sort"></span> <?=$entry->getEntry()->getInterpret() ?> - <?=$entry->getEntry()->getSongTitel() ?></div></li>
                            <?php
                        } ?>
                        </ol>
                    </div>
                    </td>
                    <td>
                        <div class="entries_list" id="entries_list">
                            <ol id="entries" class="sortable connectedSortable">
                            <?php foreach ($entries as $entry) { ?>
                                <?php if (!in_array($entry->getId(), $listentrie)) { ?>
                                <li class="handle_li" value="<?=$entry->getId() ?>"><div><span class="fa-solid fa-sort"></span> <?=$entry->getInterpret() ?> - <?=$entry->getSongTitel() ?></div></li>
                                <?php } ?>
                            <?php } ?>
                            </ol>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getListBar(['save' => 'save']); ?>
    <?=$this->getSaveBar() ?>
<?php } else { ?>
    <?php if ($entries) {?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <?php if (!$suggestion) { ?>
                <col class="icon_width">
                <?php } ?>
                <col>
                <col>
                <?php if (!$suggestion) { ?>
                <col>
                <?php } ?>
                <col>
                <col>
            </colgroup>
                <?php
                $urladd = ['order' => $sort_order == 'ASC'  ? 'desc' : 'asc'];
                if ($suggestion) {
                    $urladd['suggestion'] = 'true';
                }
                if ($filterlist) {
                    $urladd['filterlist'] = $filterlist;
                }
                ?>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <?php if (!$suggestion) { ?>
                    <th><?=$this->getTrans('list') ?></th>
                    <?php } ?>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'interpret'], $urladd)) ?>" title="<?=$this->getTrans('interpret') ?>">
                        <?=$this->getTrans('interpret') ?>
                        <i class="fa-solid fa-sort<?php echo $sort_column == 'interpret' ? '-' . str_replace(['ASC', 'DESC'], ['up', 'down'], $sort_order) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'songtitel'], $urladd)) ?>" title="<?=$this->getTrans('songtitel') ?>">
                        <?=$this->getTrans('songtitel') ?>
                        <i class="fa-solid fa-sort<?php echo $sort_column == 'songtitel' ? '-' . str_replace(['ASC', 'DESC'], ['up', 'down'], $sort_order) : ''; ?>"></i>
                        </a>
                    </th>
                    <?php if (!$suggestion) { ?>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'votes'], $urladd)) ?>" title="<?=$this->getTrans('vote') ?>">
                        <?=$this->getTrans('vote') ?>
                        <i class="fa-solid fa-sort<?php echo $sort_column == 'votes' ? '-' . str_replace(['ASC', 'DESC'], ['up', 'down'], $sort_order) : ''; ?>"></i>
                        </a>
                    </th>
                    <?php } ?>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'datecreate'], $urladd)) ?>" title="<?=$this->getTrans('datecreate') ?>">
                        <?=$this->getTrans('datecreate') ?>
                        <i class="fa-solid fa-sort<?php echo $sort_column == 'datecreate' ? '-' . str_replace(['ASC', 'DESC'], ['up', 'down'], $sort_order) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?=$this->getUrl(array_merge(['column' => 'user'], $urladd)) ?>" title="<?=$this->getTrans('user') ?>">
                        <?=$this->getTrans('user') ?>
                        <i class="fa-solid fa-sort<?php echo $sort_column == 'user' ? '-' . str_replace(['ASC', 'DESC'], ['up', 'down'], $sort_order) : ''; ?>"></i>
                        </a>
                    </th>
                </tr>
            </thead>
                <?php foreach ($entries as $entry) { ?>
                <tbody>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                        <td><?=$this->getDeleteIcon(array_merge(['action' => 'del', 'id' => $entry->getId()], (($suggestion) ? ['suggestion' => 'true'] : []))) ?></td>
                        <td><?=$this->getEditIcon(array_merge(['action' => 'treat', 'id' => $entry->getId()], (($suggestion) ? ['suggestion' => 'true'] : []))) ?></td>
                        <td>
                        <?php if (!$suggestion) { ?>
                            <a href="<?=$this->getUrl(['action' => 'update', 'id' => $entry->getId(), 'status_man' => -1], null, true) ?>">
                                <span class="fa-regular fa-<?=($entry->getSetFree() ? 'check-' : '') ?>square text-info"></span>
                            </a>
                        <?php } else { ?>
                            <a href="<?=$this->getUrl(['action' => 'suggestionenable', 'id' => $entry->getId()], null, true) ?>">
                                <span class="fa-solid fa-reply text-info"></span>
                            </a>
                        <?php } ?>
                        </td>
                        <?php if (!$suggestion) { ?>
                        <td>
                            <?php
                            $lists = $hoererchartslistMapper->getEntriesBy(['hid' => $entry->getId()], ['list' => 'ASC']) ?? [];
                            $liste = [];
                            foreach ($lists as $listsModel) {
                                $liste[] = $listsModel->getList();
                            }
                            echo implode(', ', $liste);
                            ?>
                        </td>
                        <?php } ?>
                        <td><?=$this->escape($entry->getInterpret()) ?></td>
                        <td><?=$this->escape($entry->getSongTitel()) ?></td>
                        <?php if (!$suggestion) { ?>
                            <td><?=$entry->getVotes() ?> (<?=$hoererchartsMapper->getStars($entry->getVotes(), true) ?>)</td>
                        <?php  } ?>
                        <td>
                        <?php
                            $datenow = new \Ilch\Date($entry->getDateCreate());
                            echo $datenow->format($this->getTrans('datetimeformat'));
                        ?>
                        </td>
                        <td>
                        <?php
                        $userMapper = new \Modules\User\Mappers\User();

                        $user = $userMapper->getUserById($entry->getUserId());
                        if ($user) {
                            echo $this->escape($user->getName());
                        } else {
                            echo $this->getTrans('guest');
                        }
                        ?>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>
        </table>
    </div>
        <?=$this->getListBar(array_merge((($suggestion) ? ['setfree' => 'suggestionenable'] : []), ['delete' => 'delete'])); ?>
    <?php } else { ?>
<div class="alert alert-danger">
        <?=$this->getTrans('noentriesadmin') ?>
</div>
    <?php } ?>
<?php } ?>
</form>
<?php if (!$suggestion && !$list) { ?>
    <?=$this->getDialog('radiohoererchartsModal', $this->getTrans('settings'), '<iframe style="border:0;"></iframe>'); ?>
<script>
    function votedatetime(){
        $('#radiohoererchartsModal').modal('show');
        let src = '<?=$this->getUrl(['controller' => 'settings', 'action' => 'votedatetime']) ?>';
        let height = '650px';
        let width = '100%';

        $('#radiohoererchartsModal iframe').attr({'src': src,
            'height': height,
            'width': width});
    }
    $(".btn-primary").on("click", function () {
      document.location.reload();
    });
    $("#radiohoererchartsModal").on("hide.bs.modal", function () {
        document.location.reload();
    });
    function reload() {
        setTimeout(function(){window.location.reload();}, 1000);
    }

    $(function() {
        $('#filterlist').change(function() {
            if ($(this).val() !== "") {
          window.open("<?=$this->getUrl(array_merge(['action' => 'index'], ($sort_column ? ['column' => $sort_column] : []), ($sort_order ? ['order' => $sort_order] : []))) ?>/filterlist/"+$(this).val(),"_self")
            } else {
                window.open("<?=$this->getUrl(array_merge(['action' => 'index'], ($sort_column ? ['column' => $sort_column] : []), ($sort_order ? ['order' => $sort_order] : []))) ?>","_self")
            }
        })
      })

</script>
<?php } elseif (!$list) { ?>
<script>
    $(function() {
        $('#allowsuggestion-on').change(function() {
            window.open("<?=$this->getUrl(['action' => 'allowsuggestion']) ?>","_self")
        })
        $('#allowsuggestion-off').change(function() {
            window.open("<?=$this->getUrl(['action' => 'allowsuggestion']) ?>","_self")
        })
    })
</script>
<?php } else { ?>
<script>
  $(function() {
    $('#activelist').change(function() {
      window.open("<?=$this->getUrl(['action' => 'activelist']) ?>/list/"+$(this).val(),"_self")
    })
  })
  
$('#groupForm').validate();
$(document).ready (function () {
    $('#groupForm').submit (function () {
        $('#hiddenMenu').val(JSON.stringify($('#listentries').sortable('toArray', {attribute: 'value'})));
    });

    $('#entries, #listentries').sortable({
        connectWith: ".connectedSortable",
        dropOnEmpty: true,
        placeholder: 'placeholder'
    }).disableSelection();
});

//attach on load
$(function() {
   $(".handle_li").dblclick(function(){
       if( $(this).parent().attr("id") === "entries" ){
            $(this).detach().appendTo("#listentries");
        }
        else{
            $(this).detach().appendTo("#entries");
        }
   });
});
</script>
<style>
    .connectedSortable{
        min-height: 50px;
    }
</style>
<?php } ?>
