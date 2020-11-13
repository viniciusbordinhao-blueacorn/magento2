<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Bundle\Test\Unit\Model\Plugin\Frontend;

use Magento\Bundle\Model\Plugin\Frontend\Product as ProductPlugin;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Bundle\Model\Product\Type;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /** @var  \Magento\Bundle\Model\Plugin\Product */
    private $plugin;

    /** @var  MockObject|Type */
    private $type;

    /** @var  MockObject|\Magento\Catalog\Model\Product */
    private $product;

    /**
     * @var MockObject|\Magento\Catalog\Model\ResourceModel\Product
     */
    private $resource;

    protected function setUp(): void
    {
        $this->product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTypeId'])
            ->getMock();

        $productChildIds = [
            1 => [1, 2, 5, 100500],
            12 => [7, 22, 45, 24612]
        ];
        $this->product->setData('child_ids', $productChildIds);

        $this->type = $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->setMethods(['getChildrenIds'])
            ->getMock();

        $this->plugin = new ProductPlugin($this->type);
    }

    public function testAfterGetIdentities()
    {
        $baseIdentities = [
            'SomeCacheId',
            'AnotherCacheId',
        ];
        $id = 12345;
        $childIds = [
            1 => [1, 2, 5, 100500],
            12 => [7, 22, 45, 24612]
        ];
        $expectedIdentities = [
            'SomeCacheId',
            'AnotherCacheId',
            Product::CACHE_TAG . '_' . 1,
            Product::CACHE_TAG . '_' . 2,
            Product::CACHE_TAG . '_' . 5,
            Product::CACHE_TAG . '_' . 100500,
            Product::CACHE_TAG . '_' . 7,
            Product::CACHE_TAG . '_' . 22,
            Product::CACHE_TAG . '_' . 45,
            Product::CACHE_TAG . '_' . 24612,
        ];
        $this->product->expects($this->once())
            ->method('getTypeId')
            ->willReturn(ProductType::TYPE_BUNDLE);

        $identities = $this->plugin->afterGetIdentities($this->product, $baseIdentities);
        $this->assertEquals($expectedIdentities, $identities);
    }
}
