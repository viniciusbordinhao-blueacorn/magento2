<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Bundle\Model\Plugin\Frontend;

use Magento\Bundle\Model\Product\Type;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Catalog\Model\Product\Type as ProductType;

/**
 * Add child identities to product identities on storefront.
 */
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
     * Add child identities to product identities
     *
     * @param CatalogProduct $product
     * @param array $identities
     * @return array
     */
    public function afterGetIdentities(CatalogProduct $product, array $identities): array
    {
        if ($product->getTypeId() != ProductType::TYPE_BUNDLE) {
            return array_unique($identities);
        }

        if (empty($product->getData('child_ids'))) {
            return array_unique($identities);
        }

        foreach ($product->getData('child_ids') as $childIds) {
            foreach ($childIds as $childId) {
                $identities[] = CatalogProduct::CACHE_TAG . '_' . $childId;
            }
        }

        return array_unique($identities);
    }
}
