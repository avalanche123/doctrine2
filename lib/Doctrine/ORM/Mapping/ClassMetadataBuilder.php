<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ORM\Mapping;

/**
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 */
class ClassMetadataBuilder
{
    /**
     * @var Doctrine\ORM\Mapping\ClassMetadata
     */
    protected $classMetadata;

    /**
     * @param Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     */
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }

    /**
     * @param bool $bool
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function mappedSuperclass($bool = true)
    {
        $this->classMetadata->isMappedSuperclass = (bool) $bool;
        return $this;
    }

    /**
     * @param string $name
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function table($name)
    {
        $this->classMetadata->setTableName($name);
        return $this;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function tableIndex($name, array $columns)
    {
        if ( ! isset($this->classMetadata->table['indexes'])) {
            $this->classMetadata->table['indexes'] = array();
        }
        $this->classMetadata->table['indexes'][$name] = $columns;
        return $this;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function tableUniqueConstraint($name, array $columns)
    {
        if ( ! isset($this->classMetadata->table['uniqueConstraints'])) {
            $this->classMetadata->table['uniqueConstraints'] = array();
        }
        $this->classMetadata->table['uniqueConstraints'][$name] = $columns;
        return $this;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function joinedTableInheritance()
    {
        return $this->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_JOINED);
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function singleTableInheritance()
    {
        return $this->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function tablePerClassInheritance()
    {
        return $this->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_TABLE_PER_CLASS);
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function noInheritance()
    {
        return $this->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_NONE);
    }

    /**
     * @param string $name
     * @param string $type
     * @param int $length
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function discriminatorColumn($name, $type, $length)
    {
        $this->classMetadata->setDiscriminatorColumn(array(
            'name'   => $name,
            'type'   => $type,
            'length' => $length,
        ));
        return $this;
    }

    /**
     * @param string $name
     * @param string $class
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function discriminatorMap($name, $class)
    {
        $this->classMetadata->setDiscriminatorMap(array(
            $name => $class,
        ));
        return $this;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function changeTrackingPolicyDeferredImplict()
    {
        return $this->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function changeTrackingPolicyDeferredExplicit()
    {
        return $this->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_EXPLICIT);
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function changeTrackingPolicyNotify()
    {
        return $this->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_NOTIFY);
    }

    /**
     * @param string $fieldName
     * @param string $type
     * @param array $options
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function field($fieldName, $type, array $options = array())
    {
        $this->classMetadata->mapField(array_merge(array(
            'fieldName' => $fieldName,
            'type'      => $type,
        ), $options));
        return $this;
    }

    /**
     * @param string $fieldName
     * @param string $type
     * @param array $options
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function versionField($fieldName, $type, array $options = array())
    {
        $mapping = array_merge(array(
            'fieldName' => $fieldName,
            'type'      => $type,
        ), $options);
        $this->classMetadata->setVersionMapping($mapping);
        $this->classMetadata->mapField($mapping);
        return $this;
    }

    /**
     * @param string $fieldName
     * @param string $type
     * @param array $options
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function primaryField($fieldName, $type, array $options = array())
    {
        $mapping = array_merge(array(
            'fieldName' => $fieldName,
            'type'      => $type,
            'id'        => true,
        ), $options);
        $this->classMetadata->mapField($mapping);
        return $this;
    }

    /**
     *
     * @param string $sequenseName
     * @param int $allocationSize
     * @param int $initialValue
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function sequenceGenerator($sequenseName, $allocationSize = 1, $initialValue = 1)
    {
        $this->classMetadata->setSequenceGeneratorDefinition(array(
            'sequenseName'   => $sequenseName,
            'allocationSize' => $allocationSize,
            'initialValue'   => $initialValue,
        ));
        return $this;
    }

    protected function setInheritanceType($type)
    {
        $this->classMetadata->inheritanceType = $type;
        return $this;
    }

    protected function setChangeTrackingPolicy($policy)
    {
        $this->classMetadata->setChangeTrackingPolicy($policy);
        return $this;
    }
}