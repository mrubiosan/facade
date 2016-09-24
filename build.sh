#!/bin/bash
set -ev

function install() {
    composer install
    composer -d Test/Silex1 install
    composer -d Test/Silex2 install
    composer -d Test/Symfony2 install
    composer -d Test/Symfony3 install
    composer -d Test/Zend2 install
    composer -d Test/Zend3 install
}

function test() {
    BEHATCONFIG="~/behat.yml"
    vendor/bin/phpspec run
    vendor/bin/behat --profile default
    cd ~/Test/Silex1 && vendor/bin/behat --profile silex1 -c $BEHATCONFIG
    cd ~/Test/Silex2 && vendor/bin/behat --profile silex2 -c $BEHATCONFIG
    cd ~/Test/Symfony2 && vendor/bin/behat --profile symfony2 -c $BEHATCONFIG
    if [[ ${TRAVIS_PHP_VERSION:0:3} \>= "5.5" ]]; then
      cd ~/Test/Symfony3 && vendor/bin/behat --profile symfony3 -c $BEHATCONFIG
    fi
    cd Test/Zend2 && vendor/bin/behat --profile zend2
    if [[ ${TRAVIS_PHP_VERSION:0:3} \>= "5.6" ]]; then
      cd ~/Test/Zend3 && vendor/bin/behat --profile zend3 -c $BEHATCONFIG
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

