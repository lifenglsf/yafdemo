# Yaf安装
* 使用pecl安装

    ``pecl install yaconf``
* 源码安装

    github下载yaconf源码

    ```
    git clone https://github.com/laruence/yaconf.git
    cd yaconf
    phpize
    make && make install
    ```
    在php.ini中添加extension=yaconf.so
    ```
    [root@localhost ~]# php -m |grep yaconf
     yaconf
    ```
    返回yaconf则代码安装成功
