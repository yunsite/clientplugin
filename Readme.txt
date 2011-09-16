1、简述

clientPlugin 是Sync流程的一个整要组成部分，主要是以插件形式安装到商家的站点当中，完全由商家支配调用，需要通过它来调用店谱的RESTful API。

店谱同步说明文档请查看：http://wiki.dianboom.com/index.php/同步说明


2、插件库

主要依赖于两个库文件，一个是官方的OAuthv1a库，另一个是由店谱基于OAuthv1a库封装的dianboomOAuth库，具体的源代码请到本Wiki的店谱SDK开发包中下载，地址：http://wiki.dianboom.com/index.php/SDK开发包


3、现成插件列表

如果您的网店系统是在以下列表里的，那恭喜您了，我们已经做了封装，可以为您省下一些开发的时间，当然不在列表里的就不意味着开发时间过长，只是需要贵方根据自己的系统来作相应的修改与调用处理，具体的请下载代码来看看就一目了然。

3.1、ECShop

店谱为ECShop v2.7.2做了全面的封装处理，具体的代码请到本Wiki的店谱SDK开发包中下载。

详细的开发说明请点击这里：http://wiki.dianboom.com/index.php/ECShop_clientPlugin_Dev

3.2、ShopEx

店谱为ShopEx? v4.8.5做了全面的封处理，具体的代码请到本Wiki的店谱SDK开发包中下载。

详细的开发说明请点击这里：http://wiki.dianboom.com/index.php/ShopEx_clientPlugin_Dev


--------------------------------------

有任何疑问或建议都可以联系我们

邮箱：developers (at) dianboom.com

微博：@店谱开发团队

dianboom developer team 