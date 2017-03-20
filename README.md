# yii2-presenter
 
 参考 [laracasts/presenter](https://github.com/laracasts/Presenter) 实现presenter层,
 调整了调用方式并新增了一个脚手架工具。
 
 个人理解Presenter是提供给view针对model的一种横向扩展方式,
 就实现来说是对behavior的简化版。
 
## 使用方式

presenter例子:
```
    use chenqd\presenter\BasePresenter;
    
    /**
    * @mixin \app\models\User
    * @property \app\models\User $entity
    */
    class UserPresenter extends BasePresenter {
    
        public function fullName()
        {
            return $this->first . ' ' . $this->last;
        }
        
        public function first()
        {
            return ucfirst($this->entity->first);
        }
    
    }
```

拓展类实现:
```
//不一定局限于model
use chenqd\presenter\PresentableTrait;

class User {

    use PresentableTrait;
    protected $presenter = UserPresenter::class;
    
    public $first = 'php';
    public $last = 'world';
}
```

view调用: (实现很简单可以简单翻阅下源码)
```
<h1>hello {{ $user->present()->fullName }}！</h1>
or
<h1>hello {{ $user->present('fullName') }}！</h1>
hello {{ $user->present('first') }}！
hello {{ $user->present()->first }}！
```

## 动态切换persenter
Model类处理
```php
use chenqd\presenter\PresentableTrait;

class User {

    use PresentableTrait;
    public function presenterMap()
    {
        return [
            'default' => UserPresenter::class,
            'xx' => UserPresenter::class,
        ];
    }
    
    public $first = 'php';
    public $last = 'world';
}
```

view调用: 

```
<h1>hello {{ $user->presenter('xx')->fullName }}！</h1>
or
<h1>hello {{ $user->presenter('xx', 'fullName') }}！</h1>
hello {{ $user->presenter('xx', 'first') }}！
hello {{ $user->presenter(UserPresenter::class)->first }}！
```

## 脚手架
进入console对应的配置文件添加
```php
//advance模板: common/config/main.php
//basic模板: config/console.php
return [
     //...其它配置
    'controllerMap' => [
        'presenter'=>\chenqd\presenter\PresenterController::class,
    ],
    //...其它配置
];
```
如果要指定生成文件位置
```php
'controllerMap' => [
    'presenter' => [
        'class' => \chenqd\presenter\PresenterController::class,
        'base_path' => '@common/models',
        'namespace' => 'common\models',
    ],
],
```


使用
```
./yii presenter/create User
```

## 其它
默认脚手架生成presenter到models/presenters文件下
(高级模板在common/models/presenters),
在model中使用时可以不指定 $presenter

```
use chenqd\presenter\PresentableTrait;

class User extend ActiveRecord
{
    use PresentableTrait;
}
```


