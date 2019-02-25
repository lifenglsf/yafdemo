# Yaf安装
* 使用pecl安装

    ``pecl install yaf``
* 源码安装

    github下载yaf源码

    ```
    git clone https://github.com/laruence/yaf.git
    cd yaf
    phpize
    make && make install
    ```
    在php.ini中添加extension=yaf.so
    ```
    [root@localhost ~]# php -m |grep yaf
     yaf
    ```
    返回yaf则代码安装成功
