<?php

use CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter;

describe('InputFilter\Order', function () {
    beforeEach(function () {
        $this->inputFilter = new OrderInputFilter();
    });

    describe('->isValid()', function () {
        it('should require a customer id', function () {
            $isValid = $this->inputFilter->setData([
                'customer' => ['id' => null]
            ])->isValid();

            $error = [
                'id' => [
                    'isEmpty' => 'Value is required and can\'t be empty'
                ]
            ];

            $customer = $this->inputFilter->getMessages()['customer'];

            expect($isValid)->to->be->false();
            expect($customer)->to->equal($error);
        });

        it('should require an order number', function () {
            $isValid = $this->inputFilter->setData([
                'orderNumber' => null
            ])->isValid();

            $error = [
                'isEmpty' => 'Value is required and can\'t be empty'
            ];

            $order = $this->inputFilter->getMessages()['orderNumber'];

            expect($isValid)->to->be->false();
            expect($order)->to->equal($error);
        });

        it('should require order numbers to be 13 chars long', function () {
            $scenarios = [
                [
                    'value' => '124',
                    'errors' => [
                        'stringLengthTooShort' =>
                            'The input is less than 13 characters long'
                    ]
                ],
                [
                    'value' => '20001020-0123XR',
                    'errors' => [
                        'stringLengthTooLong' => 'The input is more than 13 characters long'
                    ]
                ],
                [
                    'value' => '20040717-1841',
                    'errors' => null
                ]
            ];

            foreach ($scenarios as $scenario) {
                $isValid = $this->inputFilter->setData([
                    'orderNumber' => $scenario['value']
                ])->isValid();

                $order = null;

                if (isset($this->inputFilter->getMessages()['orderNumber'])) {
                    $order = $this->inputFilter->getMessages()['orderNumber'];
                    expect($isValid)->to->be->false();
                    expect($order)->to->equal($scenario['errors']);
                } else {
                    expect($order)->to->be->null();
                }
            }
        });

        it('should require a description', function () {
            $isValid = $this->inputFilter->setData([])->isValid();

            $error = [
                'isEmpty' => 'Value is required and can\'t be empty'
            ];

            $message = $this->inputFilter->getMessages()['description'];

            expect($isValid)->to->be->false();
            expect($message)->to->equal($error);
        });

        it('should require a total', function () {
            $scenarios = [
                [
                    'value' => 124,
                    'errors' => null
                ],
                [
                    'value' => 'asdf',
                    'errors' => [
                        'notFloat'
                        => 'The input does not appear to be a float'
                    ]
                ],
                [
                    'value' => 99.99,
                    'errors' => null
                ]
            ];
            foreach ($scenarios as $scenario) {
                $this->inputFilter = (new OrderInputFilter())->setData([
                    'total' => $scenario['value']
                ]);
                $this->inputFilter->isValid();

                $messages = null;

                if (isset($this->inputFilter->getMessages()['total'])) {
                    $messages = $this->inputFilter->getMessages()['total'];
                    expect($messages)->to->equal($scenario['errors']);
                } else {
                    expect($messages)->to->be->null();
                }
            }

        });
    });
});