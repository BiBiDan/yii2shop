<h1>菜单列表</h1>
<table class="table table-responsive">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
        <?php $children = \backend\models\Mune::find()->where(['parent_id'=>$value->id])->all()?>
        <?php if($value->parent_id==0):?>
    <tr>
        <td><?=$value->name?></td>
        <td><?=$value->url_to?'':'顶级分类'?></td>
        <td><?=$value->sort?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['mune/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['mune/delete','id'=>$value->id])?>">删除</a>
        </td>
    </tr>
        <?php endif;?>
        <?php foreach ($children as $child):?>
        <tr>
            <td><?=$child->name?></td>
            <td><?='——'.$child->url_to?></td>
            <td><?=$child->sort?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['mune/edit','id'=>$value->id])?>">修改</a>
                <a href="<?=\yii\helpers\Url::to(['mune/delete','id'=>$value->id])?>">删除</a>
            </td>
        </tr>
        <?php endforeach;?>
    <?php endforeach;?>
</table>