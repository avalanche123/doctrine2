<?php

namespace Doctrine\Tests\ORM\Mapping;

require_once __DIR__ . '/../../TestInit.php';

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataBuilder;

/**
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 */
class ClassMetadataBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    protected $builder;

    /**
     * @var Doctrine\ORM\Mapping\ClassMetadata
     */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new ClassMetadata('Doctrine\Tests\ORM\Mapping\SimpleClass');
        $this->builder = new ClassMetadataBuilder($this->metadata);
    }

    public function tearDown()
    {
        unset($this->builder, $this->metadata);
    }

    public function testGetClassMetadata()
    {
        $this->assertSame($this->metadata, $this->builder->getClassMetadata());
    }

    public function testMappedSuperclass()
    {
        $this->builder->mappedSuperclass();
        $this->assertTrue($this->metadata->isMappedSuperclass);

        $this->builder->mappedSuperclass(false);
        $this->assertFalse($this->metadata->isMappedSuperclass);
    }

    public function testTable()
    {
        $table = 'test_table';

        $this->builder->table($table);
        $this->assertEquals($table, $this->metadata->getTableName());
    }

    /**
     * @dataProvider getIndexesOrConstraints
     */
    public function testAddTableIndex(array $indexes)
    {
        $this->assertFalse(isset($this->metadata->table['indexes']));

        foreach ($indexes as $name => $columns) {
            $this->builder->tableIndex($name, $columns);
        }

        $this->assertTrue(isset($this->metadata->table['indexes']));
        $this->assertEquals($indexes, $this->metadata->table['indexes']);
    }

    /**
     * @dataProvider getIndexesOrConstraints
     */
    public function testAddTableUniqueConstraint(array $constraints)
    {
        $this->assertFalse(isset($this->metadata->table['uniqueConstraints']));

        foreach ($constraints as $name => $columns) {
            $this->builder->tableUniqueConstraint($name, $columns);
        }

        $this->assertTrue(isset($this->metadata->table['uniqueConstraints']));
        $this->assertEquals($constraints, $this->metadata->table['uniqueConstraints']);
    }

    public function testSetJoinedTableInheritance()
    {
        $this->builder->joinedTableInheritance();
        $this->assertTrue($this->metadata->isInheritanceTypeJoined());
    }

    public function testSetSingleTableInheritance()
    {
        $this->builder->singleTableInheritance();
        $this->assertTrue($this->metadata->isInheritanceTypeSingleTable());
    }

    public function testtablePerClassInheritance()
    {
        $this->builder->tablePerClassInheritance();
        $this->assertTrue($this->metadata->isInheritanceTypeTablePerClass());
    }

    public function testSetNoInheritance()
    {
        $this->builder->noInheritance();
        $this->assertTrue($this->metadata->isInheritanceTypeNone());
    }

    public function testSetDiscriminatorColumn()
    {
        $column = array(
            'name'      => 'name',
            'fieldName' => 'name',
            'type'      => 'varchar',
            'length'    => 25,
        );

        $this->builder->discriminatorColumn($column['name'], $column['type'], $column['length']);
        $this->assertEquals($column, $this->metadata->discriminatorColumn);
    }

    public function getIndexesOrConstraints()
    {
        return array(
            array(array(
                'table_index_one' => array('id', 'name'),
                'table_index_two' => array('name'),
            )),
            array(array(
                'table_index_one' => array('id'),
            )),
        );
    }
}

class SimpleClass
{
    protected $id;
    protected $name;
}