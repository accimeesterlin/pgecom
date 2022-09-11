<?php
namespace Hamcrest\Core;

class SetTest extends \Hamcrest\AbstractMatcherTest
{

    public static $_classProperty;
    public $_instanceProperty;

    protected function setUp()
    {
        self::$_classProperty = null;
        unset($this->_instanceProperty);
    }

    protected function createMatcher()
    {
        return \Hamcrest\Core\Set::set('property_name');
    }

    public function testEvaluatesToTrueIfArrayPropertyarray_key_exists()
    {
        assertThat(array('foo' => 'bar'), set('foo'));
    }

    public function testNegatedEvaluatesToFalseIfArrayPropertyarray_key_exists()
    {
        assertThat(array('foo' => 'bar'), not(notSet('foo')));
    }

    public function testEvaluatesToTrueIfClassPropertyarray_key_exists()
    {
        self::$_classProperty = 'bar';
        assertThat('Hamcrest\Core\SetTest', set('_classProperty'));
    }

    public function testNegatedEvaluatesToFalseIfClassPropertyarray_key_exists()
    {
        self::$_classProperty = 'bar';
        assertThat('Hamcrest\Core\SetTest', not(notSet('_classProperty')));
    }

    public function testEvaluatesToTrueIfObjectPropertyarray_key_exists()
    {
        $this->_instanceProperty = 'bar';
        assertThat($this, set('_instanceProperty'));
    }

    public function testNegatedEvaluatesToFalseIfObjectPropertyarray_key_exists()
    {
        $this->_instanceProperty = 'bar';
        assertThat($this, not(notSet('_instanceProperty')));
    }

    public function testEvaluatesToFalseIfArrayPropertyIsNotSet()
    {
        assertThat(array('foo' => 'bar'), not(set('baz')));
    }

    public function testNegatedEvaluatesToTrueIfArrayPropertyIsNotSet()
    {
        assertThat(array('foo' => 'bar'), notSet('baz'));
    }

    public function testEvaluatesToFalseIfClassPropertyIsNotSet()
    {
        assertThat('Hamcrest\Core\SetTest', not(set('_classProperty')));
    }

    public function testNegatedEvaluatesToTrueIfClassPropertyIsNotSet()
    {
        assertThat('Hamcrest\Core\SetTest', notSet('_classProperty'));
    }

    public function testEvaluatesToFalseIfObjectPropertyIsNotSet()
    {
        assertThat($this, not(set('_instanceProperty')));
    }

    public function testNegatedEvaluatesToTrueIfObjectPropertyIsNotSet()
    {
        assertThat($this, notSet('_instanceProperty'));
    }

    public function testHasAReadableDescription()
    {
        $this->assertDescription('set property foo', set('foo'));
        $this->assertDescription('unset property bar', notSet('bar'));
    }

    public function testDecribesPropertySettingInMismatchMessage()
    {
        $this->assertMismatchDescription(
            'was not set',
            set('bar'),
            array('foo' => 'bar')
        );
        $this->assertMismatchDescription(
            'was "bar"',
            notSet('foo'),
            array('foo' => 'bar')
        );
        self::$_classProperty = 'bar';
        $this->assertMismatchDescription(
            'was "bar"',
            notSet('_classProperty'),
            'Hamcrest\Core\SetTest'
        );
        $this->_instanceProperty = 'bar';
        $this->assertMismatchDescription(
            'was "bar"',
            notSet('_instanceProperty'),
            $this
        );
    }
}
