<head>
    <style>
        tr{
            text-align: center;
        }
    </style>
</head>
<body>
<a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info">添加</a>
<table class="table table-bordered">
    <tr style="text-align: center">
        <th>品牌id</th>
        <th>名称</th>
        <th>简介</th>
        <th>logo图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
        <tr>
            <td><?=$value->id?></td>
            <td><?=$value->name?></td>
            <td><?=$value-> intro?></td>
            <td><img src="<?=$value->logo?>" alt="" class="img img-circle" width="50px"></td>
            <td><?=$value->sort?></td>
            <td><?=$value->is_delete==0?'在线':'删除'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$value->id])?>">修改</a>
                <a href="<?=\yii\helpers\Url::to(['brand/delete','id'=>$value->id])?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
</body>
