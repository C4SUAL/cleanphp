<?php

use CleanPhp\Invoicer\Persistence\Hydrator\Strategy\DateStrategy;

describe('Persistence\Hydrator\Strategy\DateStrategy', function () {
    beforeEach(function () {
        $this->strategy = new DateStrategy();
    });

    describe('->hydrate', function () {
        it('should turn the date string into a datetime object', function () {
            $value = '2017-03-28';

            $obj = $this->strategy->hydrate($value);

            assert($obj->format('Y-m-d') === $value, 'incorrect datetime');
        });
    });

    describe('->extract()', function () {
        it('should turn the DateTime object into a string', function () {
            $date = new \DateTime('2015-09-16');

            $string = $this->strategy->extract($date);

            assert($string === $date->format('Y-m-d'));
        });
    });
});