# Teamones think-validate

Forked from top-think/think-validate v3.0.7. Existing `think\Validate` namespace and APIs are unchanged.

Install: `composer require teamones/think-validate:^3.0`

The `integer` / `int` rule validates the original input with `FILTER_VALIDATE_INT`, rather than casting first. Fractions such as `1.5` / `"1.5"`, booleans, arrays, objects, overflow and non-decimal numeric strings are rejected. Canonical signed decimal integers, zero, numeric integer strings and integral floats (for example `1.0`) remain valid. Leading-zero strings such as `"01"` and scientific notation strings such as `"1e3"` are rejected, matching the PHP integer filter used before upstream v3.0.7. Optional empty-value behavior is unchanged.

Run `composer test` under PHP 8.5 with E_ALL; tests fail on any warning or deprecation.

Original Apache-2.0 license and upstream source notices are preserved.

---

# think-validate

基于PHP8.0+ 的Validate实现

## 主要特性
- 基于PHP8和强类型实现
- 内置丰富的验证规则
- 支持验证器类、数组和链式方法定义验证规则
- 支持验证场景和验证分组
- 支持独立数据验证
- 支持枚举验证
- 支持批量验证
- 支持抛出异常


## 安装
~~~
composer require topthink/think-validate
~~~

## 用法
~~~php
use think\facade\Validate;

$validate = Validate::rule([
    'name'  => 'require|max:25',
    'email' => 'email'
]);

$data = [
    'name'  => 'thinkphp',
    'email' => 'thinkphp@qq.com'
];

if (!$validate->check($data)) {
    var_dump($validate->getError());
}
~~~

支持创建验证器进行数据验证
~~~php
<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'name'  => 'require|max:25',
        'age'   => 'number|between:1,120',
        'email' => 'email',    
    ];
    
    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',    
    ];
    
}
~~~

验证器调用代码如下：
~~~php
$data = [
    'name'  => 'thinkphp',
    'email' => 'thinkphp@qq.com',
];

$validate = new \app\index\validate\User;

if (!$validate->check($data)) {
    var_dump($validate->getError());
}
~~~

## 文档

详细使用请参考 [ThinkValidate开发指南](https://doc.thinkphp.cn/@think-validate)