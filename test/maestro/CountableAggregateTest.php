<?php
namespace maestro;

class CountableAggregateTest extends \PHPUnit_Framework_TestCase
{
    protected function createStub($expected, $instance)
    {
        $countableAggregate = $this->getMock('maestro\CountableAggregate',
                                             array('accept'));

        $countableAggregate->expects($expected)
                           ->method('accept')
                           ->with($instance)
                           ->will($this->returnValue(true));

        return $countableAggregate;
    }

    public function testAccept()
    {
        $stdClass = new \stdClass();

        $countableAggregate = $this->createStub($this->at(0), $stdClass);

        $countableAggregate->add($stdClass);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAcceptThrowsException()
    {
        $arrayObject = new \ArrayObject();

	    $countableAggregate = $this->getMock('maestro\CountableAggregate',
                                             array('accept'));

        $countableAggregate->expects($this->at(0))
                           ->method('accept')
                           ->with($arrayObject)
                           ->will($this->returnValue(false));

        $countableAggregate->add($arrayObject);
    }

    public function testAdd()
    {
        $stdClass = new \stdClass();

        $countableAggregate = $this->createStub($this->any(), $stdClass);

        $countableAggregate->add($stdClass);

        $this->assertEquals(1, $countableAggregate->count());
    }

    public function testCount()
    {
        $stdClass = new \stdClass();

        $countableAggregate = $this->createStub($this->any(), $stdClass);

        for ($i = 0; $i < 5; ++$i) {
            $this->assertEquals($i, $countableAggregate->count());
            $countableAggregate->add($stdClass);
        }
    }

    public function testGetIterator()
    {
        $stdClass = new \stdClass();

        $countableAggregate = $this->createStub($this->any(), $stdClass);

        for ($i = 0; $i < 5; ++$i) {
            $this->assertEquals($i, $countableAggregate->count());
            $countableAggregate->add($stdClass);
        }

        $iterator = $countableAggregate->getIterator();

        for ($i = 0; $iterator->valid(); $iterator->next(), ++$i) {
            $this->assertEquals($i, $iterator->key());
            $this->assertSame($stdClass, $iterator->current());
        }
    }
}
