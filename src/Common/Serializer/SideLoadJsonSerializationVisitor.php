<?php

namespace WalletAccountant\Common\Serializer;

use function array_merge;
use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\ClassMetadata;

/**
 * Copyright 2014 Paul Ferrett <paul@paulferrett.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * https://stackoverflow.com/questions/22261499/how-to-achieve-model-side-loading-with-jms-serializer-and-symfony2
 */
class SideLoadJsonSerializationVisitor extends JsonSerializationVisitor
{
    private $sideData = [];
    private $sideDataIds = [];

    /**
     * @return array
     */
    public function getRoot() {
        $root = parent::getRoot();
        if (!is_array($root) || !$root) {
            return $root;
        }
        return array_merge($root, $this->sideData);
    }

    public function endVisitingObject(ClassMetadata $metadata, $data, array $type, Context $context) {
        $rs = parent::endVisitingObject($metadata, $data, $type, $context);
        if ($context->getDepth() === 0) {
            return $rs;
        }
        return $this->addSideData($rs, $metadata, $data, $type, $context);
    }

    protected function addSideData($rs, ClassMetadata $metadata, $data, array $type, Context $context) {
        $class = $this->getSideDataClass($metadata, $data, $type);
        $id = $this->getSideDataId($metadata, $data);

        if (!$class || !$id) {
            return $rs;
        }

        if (!isset($this->sideDataIds[$class])) {
            $this->sideDataIds[$class] = [];
            $this->sideData[$class] = [];
        } else if (in_array($id, $this->sideDataIds[$class])) {
            return $id;
        }

        $this->sideDataIds[$class][] = $id;
        $this->sideData[$class][] = $rs;

        return $id;
    }

    protected function getSideDataClass(ClassMetadata $metadata, $data, array $type) {
        $className = get_class($data);
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }
        return strtolower(preg_replace(['/(s|x)$/', '/y$/'], ['$1e', 'ie'], lcfirst($className))) . 's';
    }

    protected function getSideDataId(ClassMetadata $metadata, $data) {
        if (!method_exists($data, 'getId')) {
            return null;
        }
        return $data->getId();
    }
}
