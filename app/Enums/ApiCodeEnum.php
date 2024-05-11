<?php

/**
 *   const CLIENT_HTTP_UNAUTHORIZED_BLACKLISTED = 401201;//账号在其他设备登录，请重新登录
const CLIENT_HTTP_UNAUTHORIZED = 401001; // 授权失败，请先登录
const CLIENT_BOUND_ERROR = 400106; //已被绑定
const CLIENT_NOT_FOUND_ERROR = 404001;//没有找到该页面
const SERVICE_50023 = 50023;//付款连接失效，请到商品页面重新选择付款
 *     const CLIENT_VALIDATION_ERROR = 422001; //表单验证错误
 *  const SYSTEM_CACHE_CONFIG_ERROR = 500003; //系统缓存配置出错
const SYSTEM_CACHE_MISSED_ERROR = 500004; //系统缓存失效出错

 */

namespace App\Enums;

class ApiCodeEnum
{
    const SUCCESS_DEFAULT = '200001|操作成功'; //操作成功
    const FAILED_DEFAULT = '400001|操作失败'; // 操作失败

    // 业务操作正确码：1xx、2xx、3xx 开头，后拼接 3 位
    // 200 + 001 => 200001，也就是有 001 ~ 999 个编号可以用来表示业务成功的情况，当然你可以根据实际需求继续增加位数，但必须要求是 200 开头
    // 举个栗子：你可以定义 001 ~ 099 表示系统状态；100 ~ 199 表示授权业务；200 ~ 299 表示用户业务。..
    const SERVICE_INIT_SUCCESS = 200100; //初始化成功
    const SERVICE_REGISTER_SUCCESS = '200101|注册成功'; //注册成功
    const SERVICE_LOGIN_SUCCESS = '200102|登录成功'; //登录成功
    const SERVICE_BIND_SUCCESS = 200103; //绑定成功
    const SERVICE_CHANGE_PASSWORD_SUCCESS = 200104; //修改密码成功
    const SERVICE_PAID_SUCCESS = 200105; //订单已支付
    const PAY_SUCCESS = 200106; //支付成功

    // 客户端错误码：400 ~ 499 开头，后拼接 3 位
    const CLIENT_PARAMETER_ERROR = 400002; //参数有误
    const CLIENT_DATA_EXIST = 400003; //数据已存在
    const CLIENT_DATA_NOT_FOUND = 400004; //数据不存在
    const CLIENT_TOKEN_UNAVAILABLE = '400006|token无效'; //token无效
    const CLIENT_NOT_FOUND_HTTP_ERROR = 400007; //请求失败
    const CLIENT_TRANSFER_ERROR = 400203; //数据转换失败
    const CLIENT_PAY_ERROR = 400205; //支付失败
    const CLIENT_IOS_BLUR_CLOSED_ERROR = 400206; //混淆已关闭
    // 401 - 访问被拒绝
    const CLIENT_HTTP_UNAUTHORIZED_EXPIRED = '401200|账号信息已过期，请重新登录'; //账号信息已过期，请重新登录
    // 403 - 禁止访问
    const CLIENT_ACTION_COUNT_ERROR = 400302; //操作频繁
    // 405 - 用来访问本页面的 HTTP 谓词不被允许（方法不被允许）
    const CLIENT_METHOD_HTTP_TYPE_ERROR = 405001; //HTTP请求类型错误
    const CLIENT_BOUND_EMAIL = 400207; //该邮箱已绑定过其他账号
    const CLIENT_BOUND_MOBILE = 400208; //该手机号已绑定过其他账号
    const CLIENT_BOUND_APPLE = 400209; // 该Apple ID 已绑定过其他账号
    const CLIENT_BOUND_FACEBOOK = 400210; //该Facebook ID 已绑定过其他账号
    const CLIENT_BOUND_GOOGLE = 400211; // 该Google ID 已绑定过其他账号
    const CLIENT_GOOD_ID_NO_EXIST = 400212; // 商口ID不存在
    const CLIENT_CONNECT_TOKEN_UNAVAILABLE = 400010; //connect token无效

    const CLIENT_GAME_ROLE_ERR = 404001;

    // 服务端操作错误码：500 ~ 599 开头，后拼接 3 位
    const SYSTEM_ERROR = 500001; //系统错误
    const SYSTEM_UNAVAILABLE = 500002; //系统不可用
    const SYSTEM_CONFIG_ERROR = 500005; // 系统配置有误

    // 业务操作错误码（外部服务或内部服务调用。..）
    const SERVICE_WECHAT_ALREADY_EXISTS = '500101|微信号已存在'; //微信号已存在
    const SERVICE_LOGIN_ERROR = 500102; //登陆失败
    const SERVICE_ACCOUNT_OR_PASSWORD_ERROR = '500103|账号或密码错误'; //账号或密码错误.
    const SERVICE_PASSWORD_ERROR = 500104; //旧密码不正确.
    const SERVICE_ACCOUNT_NOT_FOUND = '500205|账号不存在'; //账号不存在
    const SERVICE_ACCOUNT_DISABLED = '500206|账号被禁'; //账号被禁
    const SERVICE_ACCOUNT_CANCEL = 500207; //账号已注销.
    const SERVICE_CODE_ERROR = 500208; // 验证码不正确
    const SERVICE_ACCOUNT_ALREADY_EXISTS = '500209|账号已存在'; // 账号已存在.

    // 400 支付
    const PAY_TIME_ERROR = 500404; // 时间异常
    const PAY_TIMEOUT_ERROR = 500405; // 超过限定时间
    const PAY_TRADE_ID_ERROR = 500406; // 交易号有误
    const PAY_ORDER_REPEATED_SUBMIT = 500407; // 订单重复提交

}
