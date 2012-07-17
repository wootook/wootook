echo " * Wootook unit testing"

MODULES_DIR="`pwd`/src/application/modules"

for file in `find $MODULES_DIR -mindepth 1 -maxdepth 1 -type d`
do
    echo " * Running `basename ${file}` module tests (Unit tests)"
    phpunit --strict --colors --configuration ${file}/test/phpunit.xml ${file}/test/php/WootookUnit

    #echo " * Running `basename ${file}` module tests (Integration tests)"
    #phpunit --strict --colors --configuration ${file}/test/phpunit.xml ${file}/test/php/WootookUnit
done

