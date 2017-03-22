<?php

namespace chenqd\presenter;

use chenqd\mixin\MixinTrait;
use yii\helpers\ArrayHelper;

abstract class BasePresenter {
    use MixinTrait;

    public function get(...$keys)
    {
        $key = implode('.', $keys);
        return ArrayHelper::getValue($this, $key);
    }
} 