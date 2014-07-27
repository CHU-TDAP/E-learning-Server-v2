後端伺服器
===
主要的東西都放在Server那邊，處理手機客戶端與伺服器資料的溝通。

包含每位學生的學習資料、場地狀況、學習教材。以及處理學習路徑規劃。

## 使用需求

* PHP5.3 以上，需要有以下Extension:
    * pdo_mysql
    * zip
* MariaDB 5.5.31 (可用MySQL)

***

## 開發需求
* 支援UTF-8編碼的文字編輯器
* 開發文件產生器: phpdoc
* 自動化建置工具
    * guard（需有ruby環境）
        * guard-shell
        * guard-livereload

## 建置開發環境
### 安裝php, ruby 環境
TODO: 代補，需有`gem`, `phar`

### 安裝Guard

    gem install guard
    gem install guard-shell
    gem install guard-livereload
    
#### 瀏覽器plugin安裝
[LiveReload - browser extensions](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions-)

支援主流瀏覽器:

* Firefox
* Chrome
* Safari

### 安裝phpdoc

    pear channel-discover pear.phpdoc.org
    pear install phpdoc/phpDocumentor

## 自動化建置
很簡單，只要下以下指令即可啟動

    guard
    
啟動後會監視專案內的`.php`檔案，一有任何變動將會

* phpdoc: 重新建立開發文件
* livereload: 呼叫瀏覽器自動重新整理

#### 相關參考
* <https://github.com/guard/guard>
* <https://github.com/guard/guard/wiki/List-of-available-Guards>

## 開發文件
已將整份專案使用[PHPDocumentor](http://www.phpdoc.org/)產生出[開發文件網站](docs/index.html)

產生指令（若有使用Guard的話可省略，會自動連同一起產生）:

    phpdoc -d ./htdocs/lib -t ./docs/
