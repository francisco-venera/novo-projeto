<?php

namespace common\components\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use Faker\Provider\Uuid;

class TokenBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive token
     */
    public $tokenAttribute = 'token';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->tokenAttribute],
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return Uuid::uuid();
        }

        return parent::getValue($event);
    }

}