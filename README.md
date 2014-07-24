後端伺服器
===
主要的東西都放在Server那邊，處理手機客戶端與伺服器資料的溝通。

包含每位學生的學習資料、場地狀況、學習教材。以及處理學習路徑規劃。

## 系統需求

* PHP5.3 以上，需要有以下Extension:
    * pdo_mysql
    * zip
* MariaDB 5.5.31 (可用MySQL)


## 開發文件
已將整份專案使用[PHPDocumentor](http://www.phpdoc.org/)產生出[開發文件網站](docs/index.html)

產生指令:

    phpdoc -d ./htdocs/lib -t ./docs/
