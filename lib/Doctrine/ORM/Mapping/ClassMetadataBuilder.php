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
    public function setMappedSuperclass($bool = true)
    {
        $this->classMetadata->isMappedSuperclass = (bool) $bool;
        return $this;
    }

    /**
     * @param string $name
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function setTable($name)
    {
        $this->classMetadata->setTableName($name);
        return $this;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function addTableIndex($name, array $columns)
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
    public function addTableUniqueConstraint($name, array $columns)
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
    public function setJoinedTableInheritance()
    {
        $this->_setInheritanceType(ClassMetadata::INHERITANCE_TYPE_JOINED);
        return $this;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function setSingleTableInheritance()
    {
        $this->_setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
        return $this;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function setTablePerClassInheritance()
    {
        $this->_setInheritanceType(ClassMetadata::INHERITANCE_TYPE_TABLE_PER_CLASS);
        return $this;
    }

    /**
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function setNoInheritance()
    {
        $this->_setInheritanceType(ClassMetadata::INHERITANCE_TYPE_NONE);
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param int $length
     * @return Doctrine\ORM\Mapping\ClassMetadataBuilder
     */
    public function setDiscriminatorColumn($name, $type, $length)
    {
        $this->classMetadata->setDiscriminatorColumn(array(
            'name'   => $name,
            'type'   => $type,
            'length' => $length,
        ));
        return $this;
    }

    protected function _setInheritanceType($type)
    {
        $this->classMetadata->inheritanceType = $type;
    }
}