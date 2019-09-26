<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 15. 3. 2019
 * Time: 8:34
 */

namespace App\Helpers\Monitoring\ImportFlow;


class MonitoringAttributeSetting
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $criticalValue;

    /**
     * MonitoringAttributeSetting constructor.
     * @param int $id
     * @param string $name
     * @param int $criticalValue
     */
    public function __construct(int $id, string $name, int $criticalValue){
        $this->setId($id);
        $this->setName($name);
        $this->setCriticalValue($criticalValue);
    }

    /**
     * @return int
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * @param int $id
     * @return MonitoringAttributeSetting
     */
    public function setId(int $id): MonitoringAttributeSetting{
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * @param string $name
     * @return MonitoringAttributeSetting
     */
    public function setName(string $name): MonitoringAttributeSetting{
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCriticalValue(): int{
        return $this->criticalValue;
    }

    /**
     * @param int $criticalValue
     * @return MonitoringAttributeSetting
     */
    public function setCriticalValue(int $criticalValue): MonitoringAttributeSetting{
        $this->criticalValue = $criticalValue;
        return $this;
    }

}