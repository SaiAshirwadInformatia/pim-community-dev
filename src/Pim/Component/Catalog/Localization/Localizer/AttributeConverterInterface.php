<?php

namespace Pim\Component\Catalog\Localization\Localizer;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Convert localized attributes to default format
 *
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AttributeConverterInterface
{
    /**
     * Convert localized attributes to default format
     *
     * @param array $items
     * @param array $options
     *
     * @return mixed
     */
    public function convertToDefaultFormats(array $items, array $options = []);

    /**
     * Get list of violations return by localizers
     *
     * @return ConstraintViolationListInterface
     */
    public function getViolations();
}
