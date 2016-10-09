#!/bin/bash
set -ev
DIR=$(pwd)
function install() {
    composer install --prefer-dist -n --no-suggest
    composer install -d Test/Silex1 --prefer-dist -n --no-suggest
    composer install -d Test/Symfony2 --prefer-dist -n --no-suggest
    composer install -d Test/Zend2 --prefer-dist -n --no-suggest
    if [[ ${TRAVIS_PHP_VERSION:0:3} > "5.5" ]]; then
        composer install -d Test/Silex2 --prefer-dist -n --no-suggest
        composer install -d Test/Symfony3 --prefer-dist -n --no-suggest
    fi
    if [[ ${TRAVIS_PHP_VERSION:0:3} > "5.6" ]]; then
        composer install -d Test/Zend3 --prefer-dist -n --no-suggest
    fi
}

function test() {
    BEHATCONFIG="../../behat.yml"
    vendor/bin/phpcs --standard=PSR2 src
    vendor/bin/phpspec run
    vendor/bin/behat --profile default
    
    cd $DIR/Test/Silex1 && vendor/bin/behat --profile silex1 -c $BEHATCONFIG
    cd $DIR/Test/Symfony2 && vendor/bin/behat --profile symfony2 -c $BEHATCONFIG
    cd $DIR/Test/Zend2 && vendor/bin/behat --profile zend2 -c $BEHATCONFIG
    
    if [[ ${TRAVIS_PHP_VERSION:0:3} > "5.5" ]]; then
        cd $DIR/Test/Silex2 && vendor/bin/behat --profile silex2 -c $BEHATCONFIG
        cd $DIR/Test/Symfony3 && vendor/bin/behat --profile symfony3 -c $BEHATCONFIG
    fi
    if [[ ${TRAVIS_PHP_VERSION:0:3} > "5.6" ]]; then
        cd $DIR/Test/Zend3 && vendor/bin/behat --profile zend3 -c $BEHATCONFIG
    fi
}

case "$1" in
    install)
        install
        ;;
    test)
        test
        ;;
    *)
        exit 1
esac

