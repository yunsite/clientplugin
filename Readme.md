## 简述 ##
**clientPlugin** 是[Sync](http://wiki.dianboom.com/index.php/同步说明)流程的一个整要组成部分，主要是以插件形式安装到商家的站点当中，完全由商家支配调用，需要通过它来调用店谱的[RESTful\_API](http://wiki.dianboom.com/index.php/RESTful_API)。
## 插件库 ##
主要依赖于两个库文件，一个是官方的[OAuthv1a库](http://wiki.dianboom.com/index.php/OAuth授权机制)，另一个是由店谱基于[OAuthv1a库](http://wiki.dianboom.com/index.php/OAuth授权机制)封装的[dianboomOAuth库](http://wiki.dianboom.com/index.php/OAuth%E6%8E%88%E6%9D%83%E6%9C%BA%E5%88%B6#dianboom_OAuth)，具体的源代码请到本Wiki的[店谱SDK开发包](http://wiki.dianboom.com/index.php/SDK开发包)中下载。
## 现成插件列表 ##
如果您的网店系统是在以下列表里的，那恭喜您了，我们已经做了封装，可以为您省下一些开发的时间，当然不在列表里的就不意味着开发时间过长，只是需要贵方根据自己的系统来作相应的修改与调用处理，具体的请下载代码来看看就一目了然。
### ECShop ###
店谱为ECShop v2.7.2做了全面的封装处理，具体的代码请到本Wiki的[店谱SDK开发包](http://wiki.dianboom.com/index.php/SDK开发包)中下载。

详细的开发说明请点击这里：[ECShop\_clientPlugin\_开发说明](http://wiki.dianboom.com/index.php/ECShop_clientPlugin_Dev)
### ShopEx ###
店谱为ShopEx v4.8.5做了全面的封处理，具体的代码请到本Wiki的[店谱SDK开发包](http://wiki.dianboom.com/index.php/SDK开发包)中下载。

详细的开发说明请点击这里：[ShopEx\_clientPlugin\_开发说明](http://wiki.dianboom.com/index.php/ShopEx_clientPlugin_Dev)



---


有任何疑问或建议都可以联系我们

邮箱：developers (at) dianboom.com

微博：[@店谱开发团队](http://weibo.com/dianboomDev)

dianboom developer team