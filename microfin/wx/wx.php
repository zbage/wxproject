<?php
/*
 * 微信操作的业务服务
 *
 * @Author:chengn(https://github.com/chengn)
 * @Date:  2013-12-12
 *
*/


/**
 * Class LeJian
 *  LeJian 公众号的业务处理类
 *
 */
class MicroFin
{

    /**
     * 用户关注服务
     * @param $openid
     * @return string
     */
    public function subscribe($openid)
    {

        $text = '欢迎关注';
        return $text;
    }

    /**
     * 取消关注
     */
    public function unSubscribe()
    {
    }

    /**
     * 对文本事件的响应逻辑
     *
     * @param $openid 用户微信id
     * @param $input 用户输入文字
     * @return string   系统输出文字
     */
    public function text($openid, $input)
    {
        $text = '您输入' . $input;
        return $text;
    }

    public function btnFinance()
    {
        return '金融商城正在建设...';
    }

    public function btnGift()
    {
        return '礼品商城正在建设...';
    }

    public function btnTravel()
    {
        return '旅游商城正在建设...';
    }

    public function btnTrainers()
    {
        return '讲师顾问团正在建设...';
    }






}


?>