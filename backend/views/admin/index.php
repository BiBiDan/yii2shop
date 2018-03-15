<a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-info">添加</a>
<table class="table table-responsive">
    <tr>
        <th>管理员id</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->id?></td>
        <td><?=$value->username?></td>
        <td><?=$value->email?></td>
        <td><?=date('Y-m-d H:i:s',$value->created_at)?></td>
        <td><?=$value->updated_at?date('Y-m-d H:i:s',$value->updated_at):'0000-00-00 00:00:00'?></td>
        <td><?=$value->last_login_time?date('Y-m-d H:i:s',$value->last_login_time):'0000-00-00 00:00:00'?></td>
        <td><?=long2ip($value->last_login_ip)?></td>
        <td>
            <a href="#" class="delete">删除</a>
            <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['admin/pwd','id'=>$value->id])?>">初始化密码</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>


<?php
/**
 * @var $this \yii\web\View
 */
$url_ajax_delete = \yii\helpers\Url::to(['admin/delete']);
$this->registerJs(
        <<<JS
        $('.delete').click(function() {
            var data={};
            var tr = $(this).closest('tr');
            data['id'] = tr.find(':first').text();
            //console.log(data);
          layer.confirm('你确定删除这个用户吗？', {
        btn: ['删除','取消'] //按钮
                }, function(){
              //删除进这个函数  这里写ajax数据
              $.post('{$url_ajax_delete}',data,function(v) {
                if(v.code == 1){
                    layer.msg(v.msg, {icon: 1});
                    $(tr).remove();
                }
              },'json');
        }, function(){
            layer.msg('未被删除',{
                time:10000,//十秒后执行
                btn:['OK']
            });
            });
        });
JS
);














