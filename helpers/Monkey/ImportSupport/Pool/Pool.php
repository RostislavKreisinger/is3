<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Pool;

/**
 * Description of Pool
 *
 * @author Tomas
 */
class Pool {

    protected $data = array();
    protected $importOut;
    protected $importAll;

    public function __construct($data = array()) {
        $this->setData($data);
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
        $this->prepareData($data);
    }

    public function getImportOut() {
        return $this->importOut;
    }

    public function getImportAll() {
        return $this->importAll;
    }

    public function setImportOut($importOut) {
        $this->importOut = $importOut;
    }

    public function setImportAll($importAll) {
        $this->importAll = $importAll;
    }

    public function addImportOut($importOut) {
        $this->importOut += $importOut;
    }

    public function addImportAll($importAll) {
        $this->importAll += $importAll;
    }

    protected function prepareData($data) {
        $this->setImportAll(0);
        $this->setImportOut(0);
        foreach ($data as $row) {
            $this->addImportOut($row->out);
            $this->addImportAll($row->all);
        }
    }

}
