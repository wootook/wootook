<?php

class Test_LegaciesTest
    extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Legacies::clearAllListeners();
    }

    public function nonStaticListener($observer)
    {
        $observer->setData('success', true);
    }

    public static function staticListener($observer)
    {
        $observer->setData('success', true);
    }

    public function testRegisteringNonStaticEvent()
    {
        Legacies::registerListener('testing', array($this, 'nonStaticListener'));

        $observer = Legacies::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    public function testRegisteringStaticEvent()
    {
        Legacies::registerListener('testing', array(get_class($this), 'staticListener'));

        $observer = Legacies::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    public function testDispatchNonExistingEvent()
    {
        $observer = Legacies::dispatchEvent('testing', array('success' => true));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    /**
     * @depends testRegisteringNonStaticEvent
     */
    public function testUnregisteringNonStaticEvent()
    {
        Legacies::registerListener('testing', array($this, 'nonStaticListener'));

        Legacies::clearEventListeners('testing');

        $observer = Legacies::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertFalse($observer->getData('success'));
    }

    /**
     * @depends testRegisteringStaticEvent
     */
    public function testUnregisteringStaticEvent()
    {
        Legacies::registerListener('testing', array(get_class($this), 'staticListener'));

        Legacies::clearEventListeners('testing');

        $observer = Legacies::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertFalse($observer->getData('success'));
    }

    public function testGetSession()
    {
        $this->markTestSkipped('Sessions could not be tested in CLI.');
        $this->assertInstanceOf('Legacies_Core_Model_Session', Legacies::getSession('test'));
    }

    public function testGetTranslator()
    {
        $this->assertInstanceOf('Legacies_Core_Model_Translator', Legacies::getTranslator('test'));

        $this->assertInstanceOf('Legacies_Core_Model_Translator', Legacies::getTranslator());
    }

    public function testTranslate()
    {
        $this->assertEquals('My testing message!', Legacies::translate('test', 'My %s message!', array('testing')));
    }

    public function testTranslateGettextStyle()
    {
        $this->assertEquals('My testing message!', Legacies::__('My %s message!', 'testing'));
    }

    public function testSetDefaultLocale()
    {
        Legacies::setDefaultLocale('test');

        $this->assertAttributeEquals('test', '_defaultLocale', 'Legacies');
    }

    /**
     *
     * @requires testSetDefaultLocale
     */
    public function testGetDefaultLocale()
    {
        Legacies::setDefaultLocale('test');

        $this->assertEquals('test', Legacies::getDefaultLocale());
    }
}
