<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace WellCommerce\Bundle\ShippingBundle\Form\Admin;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Bundle\ShippingBundle\Calculator\ShippingCalculatorInterface;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ShippingMethodFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethodFormBuilder extends AbstractFormBuilder
{
    protected $calculators;
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormInterface $form)
    {
        $calculatorOptions = [];
        $calculators       = $this->getCalculators();
        
        $calculators->map(function (ShippingCalculatorInterface $calculator) use (&$calculatorOptions) {
            $calculatorOptions[$calculator->getAlias()] = $calculator->getAlias();
        });
    
        $currencies = $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
            'label_column' => 'code',
            'value_column' => 'id',
        ]);
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => $this->trans('common.fieldset.general')
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => $this->trans('common.fieldset.translations'),
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('shipping_method.repository'))
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => $this->trans('common.label.name'),
            'rules' => [
                $this->getRule('required')
            ],
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'enabled',
            'label' => $this->trans('common.label.enabled'),
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'hierarchy',
            'label' => $this->trans('common.label.hierarchy'),
            'rules' => [
                $this->getRule('required')
            ],
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'    => 'optionsProvider',
            'label'   => $this->trans('shipping_method.label.options_provider'),
            'options' => $this->getOptionProviders()
        ]));
        
        $costsData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'costs_data',
            'label' => $this->trans('shipping_method.fieldset.costs')
        ]));
        
        $costsData->addChild($this->getElement('select', [
            'name'    => 'calculator',
            'label'   => $this->trans('shipping_method.label.calculator'),
            'options' => $calculatorOptions
        ]));
        
        $costsData->addChild($this->getElement('select', [
            'name'        => 'currency',
            'label'       => $this->trans('common.label.currency'),
            'options'     => $currencies,
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('currency.repository'))
        ]));
        
        $tax = $costsData->addChild($this->getElement('select', [
            'name'        => 'tax',
            'label'       => $this->trans('common.label.tax'),
            'options'     => $this->get('tax.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('tax.repository'))
        ]));
        
        $costsData->addChild($this->getElement('range_editor', [
            'name'            => 'costs',
            'label'           => $this->trans('shipping_method.label.costs'),
            'vat_field'       => $tax,
            'range_precision' => 2,
            'transformer'     => $this->getRepositoryTransformer('shipping_cost_collection', $this->get('shipping_method_cost.repository'))
        ]));
        
        $countriesData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'countries_data',
            'label' => $this->trans('shipping_method.fieldset.countries')
        ]));
        
        $countriesData->addChild($this->getElement('multi_select', [
            'name'    => 'countries',
            'label'   => $this->trans('address.label.country'),
            'options' => $this->get('country.repository')->all(),
        ]));
    
        $this->addShopsFieldset($form);
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    private function getCalculators() : Collection
    {
        return $this->get('shipping_method.calculator.collection');
    }
    
    private function getOptionProviders() : array
    {
        $providers           = $this->get('shipping_method.options_provider.collection');
        $optionProviders[''] = $this->trans('common.label.none');
        
        foreach ($providers->getKeys() as $alias) {
            $optionProviders[$alias] = $alias;
        }
        
        return $optionProviders;
    }
}
