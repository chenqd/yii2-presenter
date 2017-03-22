<?php

namespace chenqd\presenter;

use chenqd\mixin\HasMixin;
use yii\helpers\ArrayHelper;

trait PresentableTrait
{
    use HasMixin;
    protected $presenter;
    private $_presenter_container=[];

    public function presenterMap()
    {
        if (!$this->presenter) {
            $className = strrchr(__CLASS__, "\\");
            $this->presenter = substr(__CLASS__, 0, -strlen($className)).'\\presenters'.$className.'Presenter';
        }
        return [
            'default' => $this->presenter
        ];
    }

    public function mixinMap() {
        return [
            'presenter'=>$this->presenterMap(),
        ];
    }

    public function presenter($presenter_key='default', $key=null)
    {
        return $this->mixinCall('presenter.'.$presenter_key, $key);
    }

    /**
     * Prepare a new or cached presenter instance
     *
     * @param array $keys
     * @return mixed
     */
    public function present(...$keys)
    {
        return $this->presenter('default', $keys);
    }
}