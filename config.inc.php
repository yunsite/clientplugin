<?php
/*
 *店谱网的客户端插件配置文件
 *以下只需要配置CONSUMER KEY & CONSUMER SECRET处
 *@ Consumer Key与Consumer Secret 需要发邮件到developers@dianboom.com上进行申请，
 *具体请查看文档：http://wiki.dianboom.com/index.php/同步说明
 *@ Access Token、Access Token Secret 由店谱OAuth系统自动生成，会在同步完成时返回
 */

#############################Basic setting######################################
$db_config = new stdClass;
$db_config->restURL   = 'http://api.dianboom.com';
$db_config->img_key   = 'productImage';

/*
 *DIANBOOM_CLIENTAPI_PATH是clientAPI的绝对路径，一般是放在系统的根目录上，然后配置一个常量，例如：
 *ROOT_PATH，后面跟上目录名称，当然也可以按你们系统的实际配置来修改。
 */
//$db_config->DIANBOOM_CLIENTAPI_PATH = ROOT_PATH.'clientAPI/';
$db_config->callMapping  = array(
   'addCategory'	=> 'store/addCategory', //添加商品分类
   'updateCategory'	=> 'store/updateCategory', //修改商品分类
   'deleteCategory'	=> 'store/deleteCategory/%d', //删除商品分类
   
   'addProductAttribute' => 'store/addProductAttribute', //增加产品属性选项 
   'updateProductAttribute' => 'store/updateProductAttribute', //修改产品属性选项 
   'deleteProductAttribute' => 'store/deleteProductAttribute', //删除产品属性选项 

   'addProduct'     => 'store/addProduct', //添加商品
   'updateProduct'  => 'store/updateProduct', //修改商品
   'deleteProduct'  => 'store/deleteProduct/%d', //删除商品
   
   'addProductImage'    => 'store/addProductImage', //添加商品图片
   'updateProductImage' => 'store/updateProductImage', //修改商品图片
   'deleteProductImage' => 'store/deleteProductImage/%d', //删除商品图片
   'loadBrandData' => 'store/loadBrandData', //获取品牌列表
   'loadBrandCategoriesData' => 'store/loadBrandCategoriesData', //查询并获取品牌分类
   'getStoreListByBrandId'=>'store/getStoreListByBrandId', //获取某品牌旗下所有商店列表 
   'loadSameLevelBrandCategoriesData'=>'store/loadSameLevelBrandCategoriesData', //获取同级品牌分类
   'loadCategoryData'=>'store/loadCategoryData', //获取分类信息
   'orderStatus'=>'store/orderStatus', //更改订单状态
);

#####################YOUR CONSUMER KEY & CONSUMER SECRET########################

$db_config->consumerKey			= ''; //第三方用户代号
$db_config->consumerSecret		= ''; //第三方用户密钥
$db_config->accessToken			= ''; //访问令牌
$db_config->accessTokenSecret	= ''; //访问令牌密钥

#Your dianboom account
$db_config->dianboomAccount= 'YOUR-NAME@YOUR-DOMAIN';//您的店谱帐号，一般为正常的邮箱帐号

#Import attribute mapping（载入属性代号映射表）
$db_config->mapping = require_once('mapping.php');

?>