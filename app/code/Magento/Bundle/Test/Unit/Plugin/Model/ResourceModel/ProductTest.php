<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bundle\Test\Unit\Plugin\Model\ResourceModel;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Bundle\Model\Product\Type;
use Magento\Bundle\Plugin\Model\ResourceModel\Product as Plugin;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var MockObject|Type
     */
    private $type;

    /**
     * @var MockObject|Product
     */
    private $object;

    /**
     * @var MockObject|ProductResource
     */
    private $resourceModel;

    /**
     * @var MockObject|ProductResource
     */
    private $result;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->type = $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getChildrenIds'])
            ->getMock();

        $this->object = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTypeId', 'getEntityId', 'setData', 'getData'])
            ->getMock();

        $this->resourceModel = $this->getMockBuilder(ProductResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->result = $this->getMockBuilder(ProductResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = $objectManager->getObject(
            Plugin::class,
            [
                'type' => $this->type,
            ]
        );
    }

    public function testAfterLoad()
    {
        $id = 12345;
        $childIds = [
            1 => [1, 2, 5, 100500],
            12 => [7, 22, 45, 24612]
        ];
        $this->object->expects($this->once())
            ->method('getEntityId')
            ->willReturn($id);

        $this->object->expects($this->once())
            ->method('getTypeId')
            ->willReturn(ProductType::TYPE_BUNDLE);

        $this->object->expects($this->once())
            ->method('setData')
            ->with('child_ids', $childIds);

        $this->type->expects($this->once())
            ->method('getChildrenIds')
            ->willReturn($childIds);

        $this->plugin->afterLoad($this->resourceModel, $this->result, $this->object);
    }
}
