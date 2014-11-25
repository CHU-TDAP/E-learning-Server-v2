<?php
/**
 * Password.php
 */

namespace UElearning\Util;

/**
 * 密碼以及加密相關的函式庫
 *
 * 使用範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Util/Password.php';
 *     use UElearning\Util;
 *
 *     $passUtil = new Util\Password();
 *     echo $passUtil->generator(10); // 產生10個字的密碼
 *     echo $passUtil->encrypt('abc'); // 加密此字串
 *
 *     // 核對與加密後是否吻合
 *     echo $passUtil->checkSame('a9993e364706816aba3e25717850c26c9cd0d89d', 'abc');
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Util
 */
class Password {

    /**
     * 取得亂數字串
     *
     *     The MIT License
     *
     *     Copyright (c) 2007 Tsung-Hao
     *
     *     Permission is hereby granted, free of charge, to any person obtaining a copy
     *     of this software and associated documentation files (the "Software"), to deal
     *     in the Software without restriction, including without limitation the rights
     *     to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     *     copies of the Software, and to permit persons to whom the Software is
     *     furnished to do so, subject to the following conditions:
     *
     *     The above copyright notice and this permission notice shall be included in
     *     all copies or substantial portions of the Software.
     *
     *     THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     *     IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     *     FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     *     AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     *     LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     *     OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     *     THE SOFTWARE.
     *
     * @author tsung http://plog.longwin.com.tw
     * @desc http://blog.longwin.com.tw/2007/11/php_snap_image_block_2007/
     * @param int $password_len 字串長度(幾個字)
     * @return string 亂數產生產生後的字串
     *
     */
    public function generator($password_len){
        $password = '';

        // remove o,0,1,l
        $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        // TODO: 更改成下面包含標點符號來增加安全性（目前沒加是因為暫時不想處理編碼問題）
        // $word = 'abcdefghijkmnpqrstuvwxyz!@#%^*()-ABCDEFGHIJKLMNPQRSTUVWXYZ;{}[]23456789';
        $len = strlen($word);

        for ($i = 0; $i < $password_len; $i++) {
            $password .= $word[rand() % $len];
        }

        return $password;
    }

    /**
     * 加密這段字
     *
     * @param string $text 原本字串
     * @return string 加密後結果字串
     * @since 2.0.0
     */
    public function encrypt($text){
        // 從config.php設定檔取得預設加密方式
        switch(ENCRYPT_MODE){
            case "MD5":
            case "md5":
                return $this->md5Encrypt($text);
                break;
            case "SHA1":
            case "sha1":
                return $this->sha1Encrypt($text);
                break;
            case "CRYPT":
            case "crypt":
                return $this->cryptEncrypt($text);
                break;
            default:
                return $text;
                break;
        }
    }

    /**
     * 確認是否吻合
     *
     * @param string $encrypted 已加密字串
     * @param string $text 原本字串
     * @return bool true代表與加密後字串一樣
     * @since 2.0.0
     */
    public function checkSame($encrypted, $text) {
        // 加密此字串
        $textToEncypt = $this->encrypt($text);

        // 判斷是否吻合
        if( $textToEncypt == $encrypted ) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 確認所有的加密法是否吻合
     *
     * @param string $encrypted 已加密字串
     * @param string $text 原本字串
     * @return bool true代表與加密後字串一樣
     * @since 2.0.0
     */
    public function checkSameTryAll($encrypted, $text) {
        // 判斷是否吻合
        if($encrypted == $this->encrypt($text) ||
           $encrypted == $text)
            return true;
        else {
            if (function_exists('md5')) {
                if($encrypted == $this->md5Encrypt($encrypted))
                    return true;
            }
            if (function_exists('sha1')) {
                if($encrypted == $this->sha1Encrypt($encrypted))
                    return true;
            }
//            if (function_exists('crypt')) {
//                if($encrypted == $this->cryptEncrypt($encrypted))
//                    return true;
//            }

            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * MD5加密這段字
     *
     * @param string $text 原本字串
     * @return string 加密後結果字串
     * @since 2.0.0
     */
    public function md5Encrypt($text){
        return md5($text);
    }

    /**
     * SHA1加密這段字
     *
     * @param string $text 原本字串
     * @return string 加密後結果字串
     * @since 2.0.0
     */
    public function sha1Encrypt($text){
        return sha1($text);
    }

//    /**
//     * CRYPT加密這段字
//     *
//     * @param string $text 原本字串
//     * @return string 加密後結果字串
//     * @since 2.0.0
//     */
//    public function cryptEncrypt($text){
//        return crypt($text);
//    }

}
