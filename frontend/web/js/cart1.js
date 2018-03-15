/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){
	
	//减少
	$(".reduce_num").click(function(){
		var amount = $(this).parent().find(".amount");
		if (parseInt($(amount).val()) <= 1){
			alert("商品数量最少为1");
		} else{
			$(amount).val(parseInt($(amount).val()) - 1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('goods-id');
		var amounts = $(this).parent().find('.amount').val();
        changeAmount(goods_id,amounts);
	});

	//增加
	$(".add_num").click(function(){
		var amount = $(this).parent().find(".amount");
		$(amount).val(parseInt($(amount).val()) + 1);
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('goods-id');
        var amounts = $(this).parent().find('.amount').val();
        changeAmount(goods_id,amounts);
	});

	//直接输入
	$(".amount").blur(function(){
		if (parseInt($(this).val()) < 1){
			alert("商品数量最少为1");
			$(this).val(1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('goods-id');
        var amounts = $(this).parent().find('.amount').val();
        changeAmount(goods_id,amounts);

	});
	//删除
	$(".delete").click(function () {
		if(confirm('您确定删除该购物车吗')){
			var tr = $(this).closest('tr');
			var goods_id = $(this).closest('tr').attr('goods-id');
			var amount = 0;
			changeAmount(goods_id,amount);
			tr.remove();
		}else {
			alert('没有执行删除');
		}
    });
	//ajax操作
	function changeAmount(goods_id,amount) {
		$.get('/goods/change-amount.html',{'goods_id':goods_id,'amount':amount});
    }



});