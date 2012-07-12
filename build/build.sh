echo " * Wootook unit testing"

MODULES_DIR="`pwd`/../src/application/modules"

for file in "$MODULES_DIR/*"
do
    echo " * Running ${file} module tests"
    phpunit --strict --configuration $MODULES_DIR/${file}/test/$phpunit.xml $MODULES_DIR/${file}
done

