<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-responsive">
    <tr>
        <th>商品分类id</th>
        <th>分类树</th>
        <th>名称</th>
        <th>父id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->id?></td>
        <td><?=$value->tree?></td>
        <td>
            <?php if($value->depth>=0){
                for($i=1;$i<=$value->depth;$i++){
                    echo '--';
                }
                echo $value->name;
            }?>
        </td>
        <td><?=$value->parent_id?></td>
        <td><?=$value->intro?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['goods-category/delete','id'=>$value->id])?>">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
