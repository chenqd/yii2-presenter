<?php

namespace chenqd\presenter;

use yii\helpers\ArrayHelper;

trait PresentableTrait
{
    /**
     * View presenter instance
     *
     * @var mixed
     */
    protected $presenterInstance;

    protected $presenter;
    private $_presenter_container=[];

    public function getShortClassName()
    {
        return substr(strrchr(__CLASS__, "\\"), 1);
    }

    public function presenterMap()
    {
        return [];
    }

    public function presenter($presenter_key=null, $key=null, ...$keys)
    {
        if (!is_null($key) && $keys) {
            $key .= '.'.implode('.', $keys);
        }

        if (is_null($presenter_key)) {
            return $this->present($key);
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
     * @return mixed
     * @throws PresenterException
     */
    public function present($key=null, ...$keys)
    {
        if ( ! $this->presenterInstance)
        {
            if (!$this->presenter) {
                $this->presenter = __NAMESPACE__.'\\presenters\\'.$this->getShortClassName().'Presenter';
            }

            if ( !class_exists($this->presenter))
            {
                throw new PresenterException('Please set the $presenter property to your presenter path.');
            }

            $this->presenterInstance = new $this->presenter($this);
        }

        if (!is_null($key) && $keys) {
            $key .= '.'.implode('.', $keys);
        }

        return ArrayHelper::getValue($this->presenterInstance, $key);
    }
}