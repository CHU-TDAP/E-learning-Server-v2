<?php

class ApiTemplates {

    protected $app;

    public function __construct($context) {
        $this->app = $context;
    }

    public function inputParamErr($array = array()) {

        $array += array(
            'error'       => true,
            'message'     => '',
            'message_cht' => '缺少必要的參數',
            'substatus'   => 101
        );
        $this->app->render(400, $array);
    }

    public function inputContentTypeErr() {

        $array = array(
            'error'       => true,
            'message'     => '',
            'message_cht' => '輸入參數的Content-Type不在支援範圍內 或是沒有輸入',
            'substatus'   => 102
        );
        $this->app->render(400, $array);
    }

    public function noEnableFunc($array = array()) {

        $array += array(
            'error'       => true,
            'message'     => 'This function is not enable.',
            'message_cht' => '此功能尚未開放'
        );
        $this->app->render(405, $array);
    }
}
