<?php

namespace Pim\Component\Connector\ArrayConverter\StandardToFlat;

use Akeneo\Component\StorageUtils\Repository\CachedObjectRepositoryInterface;
use Pim\Component\Catalog\Localization\Localizer\LocalizerRegistryInterface;
use Pim\Component\Connector\ArrayConverter\ArrayConverterInterface;

/**
 * Convert standard format to flat format for product with localized values.
 *
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductLocalized implements ArrayConverterInterface
{
    /** @var ArrayConverterInterface */
    protected $converter;

    /** @var CachedObjectRepositoryInterface */
    protected $attributeRepository;

    /** @var LocalizerRegistryInterface */
    protected $localizerRegistry;

    /**
     * @param ArrayConverterInterface         $converter
     * @param CachedObjectRepositoryInterface $attributeRepository
     * @param LocalizerRegistryInterface      $localizerRegistry
     */
    public function __construct(
        ArrayConverterInterface $converter,
        CachedObjectRepositoryInterface $attributeRepository,
        LocalizerRegistryInterface $localizerRegistry
    ) {
        $this->converter = $converter;
        $this->attributeRepository = $attributeRepository;
        $this->localizerRegistry = $localizerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(array $productStandard, array $options = [])
    {
        foreach ($productStandard['values'] as $code => $item) {
            $attribute = $this->attributeRepository->findOneByIdentifier($code);
            if (null !== $attribute) {
                $localizer = $this->localizerRegistry->getLocalizer($attribute->getAttributeType());

                if (null !== $localizer) {
                    $options['decimal_allowed'] = $attribute->isDecimalsAllowed();
                    foreach ($item as $index => $value) {
                        $productStandard['values'][$code][$index]['data'] = $localizer->localize($value['data'], $options);
                    }
                }
            }
        }

        $productFlat = $this->converter->convert($productStandard, $options);

        return $productFlat;
    }
}
