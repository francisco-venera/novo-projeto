<?php

namespace common\components\serializers;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\queue\serializers\SerializerInterface;

/**
 * Json Serializer.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class JsonSerializer extends BaseObject implements SerializerInterface
{
    /**
     * @var string
     */
    public $classKey = 'class';
    /**
     * @var int
     */
    public $options = 0;


    /**
     * @inheritdoc
     */
    public function serialize($job)
    {
        $teste = Json::encode($this->toArray($job), $this->options);
        return $teste; //Json::encode($this->toArray($job), $this->options);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        if (is_resource($serialized)) {
            $serialized = stream_get_contents($serialized);
        }
        $decoded = Json::decode($serialized);
        return $this->fromArray($decoded);
    }

    /**
     * @param mixed $data
     * @return array|mixed
     * @throws InvalidConfigException
     */
    protected function toArray($data)
    {
        if (is_object($data)) {
            $result = [$this->classKey => get_class($data)];
            foreach (get_object_vars($data) as $property => $value) {
                if ($property === $this->classKey) {
                    throw new InvalidConfigException("Object cannot contain $this->classKey property.");
                }
                $result[$property] = $this->toArray($value);
            }

            return $result;
        }

        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                if ($key === $this->classKey) {
                    throw new InvalidConfigException("Array cannot contain $this->classKey key.");
                }
                $result[$key] = $this->toArray($value);
            }

            return $result;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function fromArray($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        if (!isset($data[$this->classKey])) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->fromArray($value);
            }

            return $result;
        }

        $config = ['class' => $data[$this->classKey]];
        unset($data[$this->classKey]);
        foreach ($data as $property => $value) {
            $config[$property] = $this->fromArray($value);
        }

        return Yii::createObject($config);
    }
}
