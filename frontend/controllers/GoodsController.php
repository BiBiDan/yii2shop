<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/11 0011
 * Time: 下午 4:04
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Dress;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller
{
    //用户登陆跳转后页面
    public function actionIndex(){
        $model = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['model'=>$model]);
    }
    //点击商品的页面
    public function actionShow($id){
        $goodscategory = GoodsCategory::findOne(['id'=>$id]);
        //得到左右值 根据左右值遍历 查找
        $lft = $goodscategory->lft;
        $rgt = $goodscategory->rgt;
        $tree = $goodscategory->tree;
        $categorys = GoodsCategory::find()->where(['>','lft',$lft])->andWhere(['<','rgt',$rgt])->andWhere(['tree'=>$tree])->all();
        //var_dump($categorys);die;
        if(!$categorys){//如果是空的 就是有商品
            $model = Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            $model = [];
            foreach ($categorys as $category){
                $goods = Goods::find()->where(['goods_category_id'=>$category->id])->all();
                $model = array_merge($model,$goods);
                //var_dump($model);
            }
        }

        return $this->render('list',['model'=>$model]);
    }
    //高级点击商品页面
/*    public function actionShow($id){

    }*/
    //点击进入商品详情
    public function actionIntro($id){
        $good = Goods::findOne(['id'=>$id]);
        $intro = GoodsIntro::find()->where(['goods_id'=>$id])->all();
        $gallery = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $good->view_times = $good->view_times+1;
        $good->save();

        return $this->render('goods',['intro'=>$intro,'gallery'=>$gallery,'good'=>$good]);
    }

    //添加购物车
    public function actionCart($goods_id,$amount){
        //判断是不是游客 如果是游客 将购物车保存到cookie中
        if(\Yii::$app->user->isGuest){
            //获取cookie中的值
            $cookie = \Yii::$app->request->cookies;
            $value = $cookie->getValue('cart');
            if($value){//1.先判断用户有没有添加其他的物品
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //将goods_id,amount设置成键值对数组
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id] = $amount;
            }
            //将get得到的数据获取到 然后保存进cookie中
            $cookie = \Yii::$app->response->cookies;
            $cookies = new Cookie();
            $cookies->name = 'cart';
            //cookie的值需要序列化保存
            $cookies->value = serialize($carts);
            $cookies->expire = time()+7*24*3600;
            $cookie->add($cookies);
            return $this->render('cart');
        }else{//如果用户登录状态下 直接将数据添加到数据库
                $cart = Cart::findOne(['goods_id'=>$goods_id]);
                if($cart){
                    $cart->amount = $cart->amount+$amount;
                    $cart->save();
                    return $this->render('cart');
                }else{
                    $cart = new Cart();
                    $cart->member = \Yii::$app->user->id;
                    $cart->goods_id = $goods_id;
                    $cart->amount = $amount;
                    if($cart->validate()){
                        $cart->save();
                        return $this->render('cart');
                    }else{
                        var_dump($cart->getErrors());die;
                    }
                }

        }


    }
    //购物车页面
    public function actionShowCart(){
        if(\Yii::$app->user->isGuest){
            $cookie = \Yii::$app->request->cookies;
            $value = $cookie->getValue('cart');
            //var_dump($value);die;
            //判断cookie有没有值
            if($value){//有值 将其反序列化
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
        }else{//从数据库中取值
            $member_id = \Yii::$app->user->id;
            $member_carts = Cart::find()->where(['member'=>$member_id])->all();
            if($member_carts){
                foreach ($member_carts as $member_cart){
                    $carts[$member_cart->goods_id] = $member_cart->amount;
                }
            }else{
                $carts = [];
            }

        }

        return $this->render('show-cart',['carts'=>$carts]);
    }
    //ajax改变购物车
    public function actionChangeAmount($goods_id,$amount){
        //var_dump($amount);die;

        if(\Yii::$app->user->isGuest){
            $cookie = \Yii::$app->request->cookies;
            $value = $cookie->getValue('cart');
            if($value){
                    $cart = unserialize($value);
            }else{
                $cart = [];
            }
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                unset($cart[$goods_id]);
            }
            $cookies = new Cookie();
            $cookies->name = 'cart';
            $cookies->value = serialize($cart);
            $cookie = \Yii::$app->response->cookies;
            $cookie->add($cookies);
        }else{
            $cart = Cart::findOne(['goods_id'=>$goods_id]);
            if($amount == 0){
                $cart->delete();
            }else{
                $cart->amount = $amount;
                $cart->save();
            }
        }
    }
    //展示用户订单表
    public function actionOrder(){
        //判断用户登录没有 如果没有登录 将用户跳转到登录页面
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        //得到用户id 根据这个id查询出地址表 商品清单
        $mmeber_id = \Yii::$app->user->id;
        $dress = Dress::find()->where(['member_id'=>$mmeber_id])->all();
        $member_cart = Cart::find()->where(['member'=>$mmeber_id])->all();
        if($member_cart){
            foreach ($member_cart as $value){
                $cart[$value->goods_id] = $value->amount;
            }
        }else{
            $cart = [];
        }
        $payments = Payment::find()->all();
        $deliverys = Delivery::find()->all();
        return $this->render('order',['dress'=>$dress,'cart'=>$cart,'payments'=>$payments,'deliverys'=>$deliverys]);
    }
    //用户提交购物车更新 订单表和订单商品详情表
    public function actionPay(){
        $request = \Yii::$app->request;
        if($request->isPost)
                $order = new Order();
            //支付方式
            $paymemt = Payment::findOne(['id'=>$request->post('pay')]);
            $order->payment_id = $paymemt->id;
            $order->payment_name = $paymemt->payment_name;
            //地址
            $dress = Dress::findOne(['id'=>$request->post('address_id')]);
            $order->name = $dress->name;
            $order->province = $dress->province;
            $order->city = $dress->city;
            $order->area = $dress->area;
            $order->dress = $dress->dress;
            $order->tel = $dress->phone;
            //快递
            $delivery = Delivery::findOne(['id'=>$request->post('delivery')]);
            $order->delivery_id = $delivery->id;
            $order->delivery_name = $delivery->delivery_name;
            $order->delivery_price = $delivery->delivery_price;
            //创建时间 和总金额 用户id 订单状态
            $order->create_time = time();
            $order->total = 0;
            $order->member_id = \Yii::$app->user->id;
            $order->status = 1;
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                $order->save();
                //找出该用户的所有购物车
                $carts = Cart::find()->where(['member'=>\Yii::$app->user->id])->all();
                foreach ($carts as $cart){
                    $good = Goods::findOne(['id'=>$cart->goods_id]);
                    if($good->stock < $cart->amount){
                        throw new Exception('购买的'.$good->name.'商品数量不足');
                    }
                    //商品数量要减
                    $good->stock -= $cart->amount;
                    $good->save();
                    //创建订单商品详情
                    $ordergood = new OrderGoods();
                    $ordergood->order_id = $order->id;
                    $ordergood->goods_id = $good->id;
                    $ordergood->goods_name = $good->name;
                    $ordergood->logo = $good->logo;
                    $ordergood->price = $good->shop_price;
                    $ordergood->amount = $cart->amount;
                    $ordergood->total = $ordergood->price*$ordergood->amount;
                    $ordergood->save();
                    $order->total += $ordergood->total;
                }
                $order->total += $order->delivery_price;
                $order->save();
                //购物车需要删除
                Cart::deleteAll(['member'=>\Yii::$app->user->id]);
                $transaction->commit();
            }catch (Exception $e){
                $transaction->rollBack();
            }
            return $this->render('success');
        }

}