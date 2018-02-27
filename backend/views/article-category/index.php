<a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-info">添加</a>
<table class="table table-responsive">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->id?></td>
        <td><?=$value->name?></td>
        <td><?=$value->intro?></td>
        <td><?=$value->sort?></td>
        <td><?=$value->is_deleted==0?'正常':'删除'?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['article-category/delete','id'=>$value->id])?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['article/index','id'=>$value->id])?>">进入</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>