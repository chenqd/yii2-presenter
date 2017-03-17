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

    public function getShortClassName()
    {
        return substr(strrchr(__CLASS__, "\\"), 1);
    }

    /**
     * Prepare a new or cached presenter instance
     *
     * @return mixed
     * @throws PresenterException
     */
    public function present($key=null)
    {
        if (!$this->presenter) {
            $this->presenter = __NAMESPACE__.'\\presenters\\'.$this->getShortClassName().'Presenter';
        }
        
        if ( !class_exists($this->presenter))
        {
            throw new PresenterException('Please set the $presenter property to your presenter path.');
        }

        if ( ! $this->presenterInstance)
        {
            $this->presenterInstance = new $this->presenter($this);
        }

        return ArrayHelper::getValue($this->presenterInstance, $key);
    }
}