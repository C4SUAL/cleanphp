<?php

use CleanPhp\Invoicer\Service\InputFilter\CustomerInputFilter;

describe('InputFilter\Customer', function () {
    beforeEach(function () {
        $this->inputFilter = new CustomerInputFilter();
        $this->inputFilter->setData([]);
    });

    describe('->isValid()', function () {
        it('should require a name', function () {
            $result = $this->inputFilter->isValid();

            $error = [
                'isEmpty' => 'Value is required and can\'t be empty'
            ];

            $messages = $this->inputFilter->getMessages()['name'];

            expect($result)->to->equal(false);
            expect($messages)->to->equal($error);
        });

        it('should require an input', function () {
            $isValid = $this->inputFilter->isValid();

            $error = [
                'isEmpty' => 'Value is required and can\'t be empty'
            ];

            $messages = $this->inputFilter->getMessages()['email'];

            expect($isValid)->to->equal(false);
            expect($messages)->to->equal($error);
        });

        it('should required a valid email', function () {
            $scenarios = [
                [
                    'value' => 'bob',
                    'errors' => []
                ],
                [
                    'value' => 'bob@bob',
                    'errors' => []
                ],
                [
                    'value' => 'bob@bob.com',
                    'errors' => null
                ]
            ];

            foreach ($scenarios as $scenario) {
                $this->inputFilter->setData([
                    'email' => $scenario['value']
                ])->isValid();


                $messages = null;

                if (isset($this->inputFilter->getMessages()['email']) && is_array($messages)) {
                    expect($messages)->to->not->be->empty();
                } else {
                    expect($messages)->to->be->null();
                }
            }
        });
    });
});