<div><a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-primary">添加</a></div>
<div id="w0" class="form-inline"><div class="form-group field-goodssearchform-name has-success">

        <input type="text" id="goodssearchform-name" class="form-control" name="GoodsSearchForm[name]" value="" placeholder="商品名" aria-invalid="false">


    </div><div class="form-group field-goodssearchform-sn has-success">

        <input type="text" id="goodssearchform-sn" class="form-control" name="GoodsSearchForm[sn]" value="" placeholder="货号" aria-invalid="false">


    </div><div class="form-group field-goodssearchform-minprice has-success">

        <input type="text" id="goodssearchform-minprice" class="form-control" name="GoodsSearchForm[minPrice]" value="" placeholder="￥" aria-invalid="false">


    </div><div class="form-group field-goodssearchform-maxprice has-success">
        <label class="sr-only" for="goodssearchform-maxprice">-</label>
        <input type="text" id="goodssearchform-maxprice" class="form-control" name="GoodsSearchForm[maxPrice]" value="" placeholder="￥" aria-invalid="false">


    </div><button type="submit" class="btn btn-default" id="search-data"><span class="glyphicon glyphicon-search"></span>搜索</button></div>

<table class="table table-responsive">
    <tr>
        <th>商品id</th>
        <th>名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>本店售价</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>浏览次数</th>
        <th>操作</th>
    </tr>
    <tbody id="content">
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->id?></td>
        <td><?=$value->name?></td>
        <td><?=$value->sn?></td>
        <td><img src="<?=$value->logo?>" alt="" width="50px"></td>
        <td><?=$value->goodsCategory->name?></td>
        <td><?=$value->brand->name?></td>
        <td><?=$value->market_price?></td>
        <td><?=$value->shop_price?></td>
        <td><?=$value->stock?></td>
        <td><?=$value->is_on_sale==0?'在售':'下线'?></td>
        <td><?=$value->sort?></td>
        <td><?=date('Y-m-d',$value->create_time)?></td>
        <td><?=$value->view_times?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-gallery/index','id'=>$value->id])?>">相册</a>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$value->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['goods/delete','id'=>$value->id])?>">回收站</a>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
    echo \yii\widgets\LinkPager::widget([
            'pagination'=>$pager,
    ]);
    //ajax搜索
    /**
     * @var $this \yii\web\View
     */
    $ajaxindex = \yii\helpers\Url::to(['goods/ajax-index']);
    $this->registerJs(
            <<<JS
        $('#search-data').click(function() {
            
            var name = $('#goodssearchform-name').val();
            var sn = $('#goodssearchform-sn').val();
            var min = $('#goodssearchform-minprice').val();
            var max = $('#goodssearchform-maxprice').val();
            var data = {};
            data['name'] = name;
            data['sn'] = sn;
            data['min'] = min;
            data['max'] = max;
            //console.log(data);          
                  $.get('{$ajaxindex}',data,function(value) {
                      //console.log(value);
                      var html='';
                $.each(value.model,function(i,v) {   
                    //console.log(v);
                    //处理在线状态
                    if(v.is_on_sale == 0){
                        var is_on_sale = '在售'
                    }else {
                        var is_on_sale = '下线'
                    }
                //处理显示时间    
          var   date= new Date(v.create_time*1000);
          var   year=date.getYear()+1900;     
          var   month=date.getMonth()+1;     
          var   day=date.getDate();    
          var create_time = year+"-"+month+"-"+day;
                    
                    html += "<tr><td>"+v.id+"</td><td>"+v.name+"</td><td>"+v.sn+"</td><td><img src='"+v.logo+"' width='50px'></td><td>"+value.category[v.goods_category_id]+"</td><td>"+value.brand[v.brand_id]+"</td><td>"+v.market_price+"</td><td>"+v.shop_price+"</td><td>"+v.stock+"</td><td>"+is_on_sale+"</td><td>"+v.sort+"</td><td>"+create_time+"</td><td>"+v.view_times+"</td><td><a href='goods-gallery/index.html?id="+v.id+"'>相册</a>  <a href='edit.html?id="+v.id+"'>修改</a>   <a href='delete.html?id="+v.id+"'>回收站</a></td></tr>";
                    //console.log(html);
                  $('#content').html(html);
                });
                
        },'json')
    });

JS
    );