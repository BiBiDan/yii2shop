<table class="table table-responsive">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
    <tr>
        <td><?=$role->name?></td>
        <td><?=$role->description?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-role','name'=>$role->name])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['rbac/delete-role','name'=>$role->name])?>">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>