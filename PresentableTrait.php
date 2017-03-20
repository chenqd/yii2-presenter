<?php

namespace chenqd\presenter;

use yii\helpers\ArrayHelper;

trait PresentableTrait
{
    protected $presenter;
    private $_presenter_container=[];

    public function presenterMap()
    {
        if (!$this->presenter) {
            $this->presenter = __NAMESPACE__.'\\presenters\\'.substr(strrchr(__CLASS__, "\\"), 1).'Presenter';
        }
        return [
            'default' => $this->presenter
        ];
    }

    public function presenter($presenter_key='default', $key=null)
    {
        if (is_array($key)) {
            $key = implode('.', $key);
        }

        if (!isset($this->_presenter_container[$presenter_key])) {
            $class = ArrayHelper::getValue($this->presenterMap(), $presenter_key, $presenter_key);
            $this->_presenter_container[$presenter_key] = \Yii::createObject($class, [$this]);
        }

        return ArrayHelper::getValue($this->_presenter_container[$presenter_key], $key);
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