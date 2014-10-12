後端伺服器
===
主要的東西都放在Server那邊，處理手機客戶端與伺服器資料的溝通。

包含每位學生的學習資料、場地狀況、學習教材。以及處理學習路徑規劃。

## 使用需求

* PHP5.3 以上，需要有以下Extension:
    * pdo-mysql
    * zip
    * mcrypt
* MariaDB 5.5.31 (可用MySQL)

## 建置伺服器環境

### 1. 安裝Apache + MySQL

For Linux Debian, Ubuntu:

    # 更新套件庫、升級系統內套件
    sudo apt-get update; sudo apt-get upgrade -y
    # 安裝Apache, MySQL, PHP(附帶需要的Extensions)
    sudo apt-get install apache2 mysql-server php5 php5-mysql php5-mcrypt phpmyadmin -y

### 2. 編輯Apache網站設定檔(vhost)

1. 編輯以下文件:
    * ArchLinux: `/etc/httpd/conf/extra/httpd-vhosts.conf`
    * Ubuntu: `/etc/apache2/sites-available/uelearning.conf`
    
    加入以下內容:
    
        <VirtualHost *:80>
            ServerName uelearning.yourdomain.name
            ServerAdmin admin@yourdomain.name
            
            DocumentRoot /srv/http/website/E-learning-Server/htdocs
            DirectoryIndex index.php index.shtml index.html
        </VirtualHost>
        <Directory /srv/http/website/E-learning-Server/htdocs/>
            Options FollowSymLinks MultiViews
            AllowOverride All
            Allow from All
            Order allow,deny
            Require all granted #Apache 2.4以上版本需加此行，若在Apache2.2請移除此行
        </Directory>
    
2. 啟用本站/重新啟動伺服器:
    * ArchLinux: `$ sudo systemctl restart httpd.service`
    * Debian, Ubuntu:
        1. `$ sudo a2ensite uelearning`
        2. `$ service apache2 reload`

### 3. 編輯Apache設定
Linux Debian: `sudo a2enmod rewrite`

非Debian or Ubuntu的，請開啟以下設定檔:

* Windows: 到`C:\AppServ\Apache2.2\conf\httpd.conf`
* Arch Linux: 到`/etc/httpd/conf/httpd.conf`
    
將 `LoadModule rewrite_module modules/mod_rewrite.so` 取消註解
        
### 4. 編輯PHP設定

開啟以下設定檔:

* Windows: 到`C:\Windows\php.ini
* Arch Linux: 到`/etc/php/php.ini`

找到`output_buffering`那行修改成 `output_buffering = On`。（`output_buffering = 4096`也OK）

並將`extension=php_pdo.dll`和`extension=php_pdo_mysql.dll`取消註解。（Linux請把`.so`當成`.dll`看待）
    
### 5. 啟用本站/重新啟動伺服器:

* Windows: 
    1. `C:\AppServ\Apache2.2\apache_serviceuninstall.bat`
    2. `C:\AppServ\Apache2.2\apache_serviceinstall.bat`
* ArchLinux: `$ sudo systemctl restart httpd.service`
* Ubuntu: `$ sudo service apache2 reload`

## 安裝此系統
請擇一選擇安裝方式:
### 引導式安裝
**>>> 施工中，請勿使用 <<<**

### 手動安裝
1. 請先把 `/htdocs/` 整個複製到你的網頁空間
2. 將內附的 `/sql/UElearning.sql` 匯入進你的資料庫
3. 將 `/htdocs/config.sample.php` 檔案複製成 `config.php` ，並依你的需求修改。


***

## 開發需求
* 支援UTF-8編碼的文字編輯器
* 開發文件產生器: phpdoc
* 單元測試: phpunit
* 自動化建置工具
    * guard（需有ruby環境）
        * guard-shell
        * guard-livereload
        * guard-phpunit2 (不是guard-phpunit)

## 建置開發環境
### 安裝PHP套件管理程式-pear
    sudo apt-get install php-pear

### 安裝PHPDoc, PHPUnit
安裝PHPDoc:

    sudo pear channel-discover pear.phpdoc.org
    sudo pear install phpdoc/phpDocumentor

安裝PHPUnit: 

    sudo pear channel-discover pear.phpunit.de
    sudo pear install phpunit/PHPUnit

### 安裝自動化工具
安裝Guard所需套件

    sudo apt-get install g++ gem wget
    sudo gem install bundler
    
安裝此專案所需的套件
    
    cd E-learning-Server # 進入專案資料夾
    bundler install
    bundle exec guard
    
PS. 若出現`ArgumentError: invalid byte sequence in US-ASCII`錯誤，是因為Ruby<2.0 以下版本預設編碼是採用**US-ASCII**，必須下以下兩行指令來修正此問題:

    export LANG="C.UTF-8"
    export LC_ALL="C.UTF-8"
    
為了減少每次都要下此兩行的麻煩，建議可寫在`~/.bashrc`裡，自動指定編碼。
    
### 瀏覽器plugin安裝
[LiveReload - browser extensions](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions-)

支援主流瀏覽器: Firefox, Chrome, Safari

## 自動化建置 (開發前建議啟動)
撰寫程式前，可在專案內下以下指令即可啟動

    guard
    
啟動後會監視專案內的`.php`檔案，一有任何變動將會

* phpdoc: 重新建立開發文件
* livereload: 呼叫瀏覽器自動重新整理
* phpunit2: 單元測試是否可成功執行

#### 相關參考
* <https://github.com/guard/guard>
* <https://github.com/guard/guard/wiki/List-of-available-Guards>

## 開發文件
已將整份專案使用[PHPDocumentor](http://www.phpdoc.org/)產生出[開發文件網站](docs/index.html)

PS. 若有使用Guard的話可不需手動下此指令，會自動連同一起產生

產生指令:

    phpdoc -d ./htdocs/lib -t ./docs/

## 單元測試
PS. 若有使用Guard的話，會自動對你正在編輯的檔案進行測試

測試指令：

    cd test # 進入測試資料夾
    phpunit --bootstrap ../htdocs/config.php <要測試的檔案>