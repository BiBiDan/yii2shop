<a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-responsive">
    <tr>
        <th>id</th>
        <th>标题</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->id?></td>
        <td><?=$value->name?></td>
        <td><?=substr($value->intro,0,100).'...'?></td>
        <td><?=$value->articleCategory->name?></td>
        <td><?=$value->sort?></td>
        <td><?=$value->is_deleted==0?"正常":"删除"?></td>
        <td><?=date('Y-m-d',$value->create_time)?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['article/delete','id'=>$value->id])?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['article-detail/index','id'=>$value->id])?>">查看详情</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>