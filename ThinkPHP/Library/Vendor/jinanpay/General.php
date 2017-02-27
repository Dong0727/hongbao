<?php
/**
 * Created by PhpStorm.
 * User: xiaodu
 * Date: 2016/10/13
 * Time: 18:01
 */
class Generals{
    //测试商户：678110154110001
    const merchno = '678440148160004';
    //测试密钥：0123456789ABCDEF0123456789ABCDEF
    const signature = 'D956EE21C1827B6322AF83B36BD778BC';
    //测试返回通知地址：http://110.172.212.221:18080/MyTest/servlet/Result/WxpayAPI_php_v3/payback.php
    const notifyUrl = 'http://110.172.212.221:18080/MyTest/servlet/Result/WxpayAPI_php_v3/payback.php';
    //流水号表头（自定义）
    const traceno = '888000';

    //t0必须要传的参数
    const certno="收款人身份证号"; //收款人身份证号
    const mobile="收款人手机号码"; //收款人手机号码
    const accountno="收款人账号"; //收款人账号
    const accountName="收款人姓名"; //收款人姓名
    const bankno="收款联行号"; //收款联行号
    const bankName="收款银行支行名称"; //收款银行支行名称
    const bankType="收款银行类别名称"; //收款银行类别名称

}