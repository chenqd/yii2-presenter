<?php

namespace chenqd\presenter;

use yii\helpers\ArrayHelper;

abstract class BasePresenter {

	/**
	 * @var mixed
	 */
	protected $entity;

	/**
	 * @param $entity
	 */
	function __construct($entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Allow for property-style retrieval
	 *
	 * @param $property
	 * @return mixed
	 */
	public function __get($property)
	{
		if (method_exists($this, $property))
		{
			return $this->{$property}();
		}

		return $this->entity->{$property};
	}

    public function __isset($property)
    {
        if (method_exists($this, $property))
        {
            return true;
        }

        return isset($this->entity->{$property});
    }

    public function get(...$keys)
    {
        $key = implode('.', $keys);
        return ArrayHelper::getValue($this, $key);
    }

} 