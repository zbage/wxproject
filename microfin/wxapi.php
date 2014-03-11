<?php
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * 
 */
header("Content-Type: text/html;charset=utf-8");

require('wx/Wechat.php');
require('wx/wx.php');



/**
 * 微信公众平台入口
 */
class MyWechat extends Wechat
{

    /**
     * 加载服务对象,具体公众号的服务对象
     */
    protected function load()
    {
        //载入具体的服务类
        $this->service = new MicroFin();
    }

    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe()
    {
        $openid = $this->getRequest('fromusername');
        $text = $this->service->subscribe($openid);
        $this->responseText($text);
    }

    /**
     * 用户取消关注时触发
     *
     * @return void
     */
    protected function onUnsubscribe()
    {
        // 「悄悄的我走了，正如我悄悄的来；我挥一挥衣袖，不带走一片云彩。」
    }

    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     * 通过数据库来匹配指定的回复
     * @return void
     */
    protected function onText()
    {
        $openid = $this->getRequest('fromusername');
        $input = $this->getRequest('content');
        $text = $this->service->text($openid, $input);
        $this->responseText($text);
    }

    /**
     * 收到图片消息时触发，回复由收到的图片组成的图文消息
     *
     * @return void
     */
    protected function onImage()
    {
        $items = array(
            new NewsResponseItem('标题一', '描述一', $this->getRequest('picurl'), $this->getRequest('picurl')),
            new NewsResponseItem('标题二', '描述二', $this->getRequest('picurl'), $this->getRequest('picurl')),
        );

        $this->responseNews($items);
    }

    /**
     * 收到地理位置消息时触发，回复收到的地理位置
     *
     * @return void
     */
    protected function onLocation()
    {
        $num = 1 / 0;
        // 故意触发错误，用于演示调试功能

        $this->responseText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
    }

    /**
     * 收到链接消息时触发，回复收到的链接地址
     *
     * @return void
     */
    protected function onLink()
    {
        $this->responseText('收到了链接：' . $this->getRequest('url'));
    }

    /**
     * 收到未知类型消息时触发，回复收到的消息类型
     *
     * @return void
     */
    protected function onUnknown($type)
    {
        $this->responseText('收到未知类型 ' . $type);
    }

    /**
     *  click事件响应
     */
    protected function onClick()
    {
        $openid = $this->getRequest('fromusername');
        $text = '正在建设...';
        switch ($this->getRequest('eventkey')) {
            case 'btn_finance':
                $text =  $this->service->btnFinance();
                break;
            case 'btn_gift':
                $text = $this->service->btnGift();
                break;
            case 'btn_travel':
                $text = $this->service->btnTravel();
                break;
            case 'btn_trainers':
                $text = $this->service->btnTrainers();
                break;
            default:
                $text = '收到未知类型 ' . $this->getRequest('eventkey');
                break;
        }
        $this->responseText($text);
    }

}

//实例微信处理类，并且响应请求。
$wechat = new MyWechat('microfin', TRUE);
$wechat->run();

