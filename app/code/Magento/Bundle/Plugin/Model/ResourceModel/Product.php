<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\Bundle\Plugin\Model\ResourceModel;

use Magento\Bundle\Model\Product\Type;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\ResourceModel\Product as ResourceModel;

class Product
{
    /**
     * @var Type
     */
    private $type;

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @param ResourceModel $resource
     * @param $result
     * @param $object
     * @return ResourceModel
     */
    public function afterLoad(ResourceModel $resource, $result, $object)
    {
        if ($object->getTypeId() == ProductType::TYPE_BUNDLE) {
            $ids = $this->type->getChildrenIds($object->getEntityId());
            $object->setData('child_ids', $ids);
        }

        return $resource;
    }
}
