<?php

class Test_WootookTest
    extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Wootook::clearAllListeners();
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
        Wootook::registerListener('testing', array($this, 'nonStaticListener'));

        $observer = Wootook::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    public function testRegisteringStaticEvent()
    {
        Wootook::registerListener('testing', array(get_class($this), 'staticListener'));

        $observer = Wootook::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    public function testDispatchNonExistingEvent()
    {
        $observer = Wootook::dispatchEvent('testing', array('success' => true));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertTrue($observer->getData('success'));
    }

    /**
     * @depends testRegisteringNonStaticEvent
     */
    public function testUnregisteringNonStaticEvent()
    {
        Wootook::registerListener('testing', array($this, 'nonStaticListener'));

        Wootook::clearEventListeners('testing');

        $observer = Wootook::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertFalse($observer->getData('success'));
    }

    /**
     * @depends testRegisteringStaticEvent
     */
    public function testUnregisteringStaticEvent()
    {
        Wootook::registerListener('testing', array(get_class($this), 'staticListener'));

        Wootook::clearEventListeners('testing');

        $observer = Wootook::dispatchEvent('testing', array('success' => false));

        $this->assertInstanceOf('Legacies_Core_Event', $observer);
        $this->assertFalse($observer->getData('success'));
    }

    public function testGetSession()
    {
        $this->markTestSkipped('Sessions could not be tested in CLI.');
        $this->assertInstanceOf('Legacies_Core_Model_Session', Wootook::getSession('test'));
    }

    public function testGetTranslator()
    {
        $this->assertInstanceOf('Legacies_Core_Model_Translator', Wootook::getTranslator('test'));

        $this->assertInstanceOf('Legacies_Core_Model_Translator', Wootook::getTranslator());
    }

    public function testTranslate()
    {
        $this->assertEquals('My testing message!', Wootook::translate('test', 'My %s message!', array('testing')));
    }

    public function testTranslateGettextStyle()
    {
        $this->assertEquals('My testing message!', Wootook::__('My %s message!', 'testing'));
    }

    public function testSetDefaultLocale()
    {
        Wootook::setDefaultLocale('test');

        $this->assertAttributeEquals('test', '_defaultLocale', 'Beyond');
    }

    /**
     *
     * @requires testSetDefaultLocale
     */
    public function testGetDefaultLocale()
    {
        Wootook::setDefaultLocale('test');

        $this->assertEquals('test', Wootook::getDefaultLocale());
    }
}
