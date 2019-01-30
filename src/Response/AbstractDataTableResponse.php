<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 13:15
 */

namespace WebAppId\User\Response;


abstract class AbstractDataTableResponse extends AbstractResponse
{
    private $recordsFiltered;
    private $recordsTotal;
    
    /**
     * @return mixed
     */
    public function getRecordsFiltered(): ?int
    {
        return $this->recordsFiltered;
    }
    
    /**
     * @param mixed $recordsFiltered
     */
    public function setRecordsFiltered($recordsFiltered): void
    {
        $this->recordsFiltered = $recordsFiltered;
    }
    
    /**
     * @return mixed
     */
    public function getRecordsTotal(): ?int
    {
        return $this->recordsTotal;
    }
    
    /**
     * @param mixed $recordsTotal
     */
    public function setRecordsTotal($recordsTotal): void
    {
        $this->recordsTotal = $recordsTotal;
    }
    
    
}