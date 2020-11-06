<h1><?=$this->getTrans('reset') ?></h1>
<div class="form-group">
    <a href="<?=$this->getUrl(['action' => 'reset', 'reset' => true], null, true) ?>" class="btn btn-danger btn-lg active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('reset') ?></a>
</div>

<div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('user') ?></th>
                    <th><?=$this->getTrans('datecreate') ?></th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry) {
    ?>
                <tbody>
                    <tr>
                        <td>
                        <?php
                            $userMapper = new \Modules\User\Mappers\User();
                            
    $user_id = $entry->getUser_Id();
    $user = $userMapper->getUserById($user_id);
    if ($user) {
        echo $this->escape($user->getName());
    } else {
        echo $this->getTrans('guest');
    } ?>
                        </td>
                        <td>
                        <?php
                            $datenow = new \Ilch\Date($entry->getLast_Activity());
    echo $datenow->format($this->getTrans('datetimeformat')); ?>
                        </td>
                    </tr>
                </tbody>
            <?php
} ?>
        </table>
    </div>
