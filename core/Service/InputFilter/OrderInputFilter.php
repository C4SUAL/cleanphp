<?php

namespace CleanPhp\Invoicer\Service\InputFilter;

use Zend\I18n\Validator\IsFloat;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class OrderInputFilter extends InputFilter
{
    public function __construct()
    {
        $customer = (new InputFilter())->add(
            (new Input('id'))->setRequired(true)
        );

        $orderNumber = (new Input('orderNumber'))->setRequired(true);
        $orderNumber->getValidatorChain()->attach(
            new StringLength(['min' => 13, 'max' => 13])
        );

        $description = (new Input('description'))->setRequired(true);

        $total = (new Input('total'))->setRequired(true);
        $total->getValidatorChain()->attach(
            new IsFloat()
        );

        $this->add($customer, 'customer');
        $this->add($orderNumber);
        $this->add($description);
        $this->add($total);
    }
}