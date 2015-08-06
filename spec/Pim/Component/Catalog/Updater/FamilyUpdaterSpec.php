<?php

namespace spec\Pim\Component\Catalog\Updater;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Entity\FamilyTranslation;
use Pim\Bundle\CatalogBundle\Factory\AttributeRequirementFactory;
use Pim\Bundle\CatalogBundle\Factory\FamilyFactory;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Pim\Bundle\CatalogBundle\Model\AttributeRequirementInterface;
use Pim\Bundle\CatalogBundle\Model\ChannelInterface;
use Pim\Bundle\CatalogBundle\Model\FamilyInterface;
use Pim\Bundle\CatalogBundle\Repository\AttributeRepositoryInterface;
use Pim\Bundle\CatalogBundle\Repository\ChannelRepositoryInterface;
use Pim\Bundle\CatalogBundle\Repository\FamilyRepositoryInterface;
use Prophecy\Argument;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class FamilyUpdaterSpec extends ObjectBehavior
{
    function let(
        FamilyRepositoryInterface $familyRepository,
        FamilyFactory $familyFactory,
        AttributeRepositoryInterface $attributeRepository,
        ChannelRepositoryInterface $channelRepository,
        AttributeRequirementFactory $attrRequiFactory
    ) {
        $this->beConstructedWith(
            $familyRepository,
            $familyFactory,
            $attributeRepository,
            $channelRepository,
            $attrRequiFactory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Component\Catalog\Updater\FamilyUpdater');
    }

    function it_is_a_updater()
    {
        $this->shouldImplement('Akeneo\Component\StorageUtils\Updater\ObjectUpdaterInterface');
    }

    function it_throws_an_exception_when_trying_to_update_anything_else_than_a_family()
    {
        $this->shouldThrow(
            new \InvalidArgumentException(
                'Expects a "Pim\Bundle\CatalogBundle\Model\FamilyInterface", "stdClass" provided.'
            )
        )->during(
            'update',
            [new \stdClass(), []]
        );
    }

    function it_updates_a_family(
        $attrRequiFactory,
        $channelRepository,
        FamilyTranslation $translation,
        FamilyInterface $family,
        AttributeRepositoryInterface $attributeRepository,
        AttributeInterface $skuAttribute,
        AttributeInterface $nameAttribute,
        AttributeInterface $descAttribute,
        AttributeInterface $priceAttribute,
        AttributeRequirementInterface $skuMobileRqrmt,
        AttributeRequirementInterface $nameMobileRqrmt,
        AttributeRequirementInterface $skuPrintRqrmt,
        AttributeRequirementInterface $namePrintRqrmt,
        AttributeRequirementInterface $descPrintRqrmt,
        ChannelInterface $mobileChannel,
        ChannelInterface $printChannel,
        FamilyInterface $family
    ) {
        $values = [
            'code'                => 'mycode',
            'attributes'          => ['sku', 'name', 'description', 'price'],
            'attribute_as_label'  => 'name',
            'requirements'        => [
                'mobile' => ['sku', 'name'],
                'print'  => ['name', 'description'],
            ],
            'labels'              => [
                'fr_FR' => 'Moniteurs',
                'en_US' => 'PC Monitors',
            ],
        ];

        $attributeRepository->findOneByIdentifier('sku')->willReturn($skuAttribute);
        $attributeRepository->findOneByIdentifier('name')->willReturn($nameAttribute);
        $attributeRepository->findOneByIdentifier('description')->willReturn($descAttribute);
        $attributeRepository->findOneByIdentifier('price')->willReturn($priceAttribute);
        $attributeRepository->getIdentifier()->willReturn($skuAttribute);

        $skuAttribute->getAttributeType()->willReturn('pim_catalog_identifier');
        $nameAttribute->getAttributeType()->willReturn('pim_catalog_text');
        $descAttribute->getAttributeType()->willReturn('pim_catalog_textarea');
        $priceAttribute->getAttributeType()->willReturn('pim_catalog_price_collection');

        $channelRepository->findOneByIdentifier('mobile')->willReturn($mobileChannel);
        $channelRepository->findOneByIdentifier('print')->willReturn($printChannel);

        $attrRequiFactory->createAttributeRequirement($skuAttribute, $mobileChannel, true)->willReturn($skuMobileRqrmt);
        $attrRequiFactory->createAttributeRequirement($nameAttribute, $mobileChannel, true)->willReturn($nameMobileRqrmt);
        $attrRequiFactory->createAttributeRequirement($skuAttribute, $printChannel, true)->willReturn($skuPrintRqrmt);
        $attrRequiFactory->createAttributeRequirement($nameAttribute, $printChannel, true)->willReturn($namePrintRqrmt);
        $attrRequiFactory->createAttributeRequirement($descAttribute, $printChannel, true)->willReturn($descPrintRqrmt);

        $family
            ->setAttributeRequirements(
                [
                    $skuMobileRqrmt,
                    $nameMobileRqrmt,
                    $namePrintRqrmt,
                    $descPrintRqrmt,
                    $skuPrintRqrmt
                ]
            )
            ->shouldBeCalled();

        $family->setCode('mycode')->shouldBeCalled();

        $family->addAttribute($skuAttribute)->shouldBeCalled();
        $family->addAttribute($nameAttribute)->shouldBeCalled();
        $family->addAttribute($skuAttribute)->shouldBeCalled();
        $family->addAttribute($skuAttribute)->shouldBeCalled();

        $family->setLocale('en_US')->shouldBeCalled();
        $family->setLocale('fr_FR')->shouldBeCalled();
        $family->getTranslation()->willReturn($translation);

        $translation->setLabel('label en us');
        $translation->setLabel('label fr fr');

        $family->addAttribute($skuAttribute)->shouldBeCalled();
        $family->addAttribute($nameAttribute)->shouldBeCalled();
        $family->addAttribute($descAttribute)->shouldBeCalled();
        $family->addAttribute($priceAttribute)->shouldBeCalled();

        $family->setAttributeAsLabel($nameAttribute)->shouldBeCalled();

        $this->update($family, $values, []);
    }

    public function it_throws_an_exception_if_attribute_does_not_exist(FamilyInterface $family, $attributeRepository)
    {
        $data = [
            'code'                => 'mycode',
            'attributes'          => ['sku', 'name', 'description', 'price'],
            'attribute_as_label'  => 'name',
            'requirements'        => [
                'mobile' => ['sku', 'name'],
                'print'  => ['sku', 'name', 'description'],
            ],
            'labels'              => [
                'fr_FR' => 'Moniteurs',
                'en_US' => 'PC Monitors',
            ],
        ];

        $attributeRepository->findOneByIdentifier('sku')->willReturn(null);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Attribute with "%s" code does not exist', 'sku')))
            ->during('update', [$family, $data]);
    }

    function it_throws_an_exception_if_attribute_not_found(
        $attributeRepository,
        FamilyInterface $family
    ) {
        $data = [
            'code'                => 'mycode',
            'attributes'          => ['sku', 'name', 'description', 'price'],
            'attribute_as_label'  => 'name',
            'requirements'        => [
                'mobile' => ['sku', 'name'],
                'print'  => ['sku', 'name', 'description'],
            ],
            'labels'              => [
                'fr_FR' => 'Moniteurs',
                'en_US' => 'PC Monitors',
            ],
        ];

        $attributeRepository->findOneByIdentifier('sku')->willReturn(null);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Attribute with "%s" code does not exist', 'sku')))
            ->during('update', [$family, $data]);
    }

    function it_throws_an_exception_if_channel_not_found(
        $channelRepository,
        $attributeRepository,
        AttributeInterface $attribute,
        FamilyInterface $family
    ) {
        $data = [
            'code'                => 'mycode',
            'attributes'          => ['sku', 'name', 'description', 'price'],
            'attribute_as_label'  => 'name',
            'requirements'        => [
                'mobile' => ['sku', 'name'],
                'print'  => ['sku', 'name', 'description'],
            ],
            'labels'              => [
                'fr_FR' => 'Moniteurs',
                'en_US' => 'PC Monitors',
            ],
        ];

        $attributeRepository->findOneByIdentifier('sku')->willReturn($attribute);
        $attributeRepository->findOneByIdentifier('name')->willReturn($attribute);
        $attributeRepository->findOneByIdentifier('description')->willReturn($attribute);
        $attributeRepository->findOneByIdentifier('price')->willReturn($attribute);
        $channelRepository->findOneByIdentifier('print')->willReturn(null);
        $channelRepository->findOneByIdentifier('mobile')->willReturn(null);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Channel with "%s" code does not exist', 'mobile')))
            ->during('update', [$family, $data]);
    }
}