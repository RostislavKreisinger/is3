<?php

namespace App\Helpers\ProjectIco;


use Exception;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\DateTime\DateTimeHelper;

class EshopProvider {

    /**
     * @var int
     */
    private $eshopId;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $eshop;

    /**
     * EshopProvider constructor.
     * @param $eshopId
     */
    public function __construct(int $eshopId) {
        $this->eshopId = $eshopId;
    }

    /**
     * @param string $ico
     * @throws Exception
     */
    public function updateIco(string $ico) {
        $eshop = $this->getEshop();
        if(empty($ico)){
            $ico = null;
        }
        MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")->where("id", "=", $eshop->id)
            ->update(["ico" => $ico, "skip_until_at" => null]);
    }

    /**
     * @param string $nationality
     * @throws Exception
     */
    public function updateNationality(string $nationality) {
        $eshop = $this->getEshop();
        if(empty($nationality)){
            $nationality = null;
        }
        MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")->where("id", "=", $eshop->id)
            ->update(["nationality" => $nationality, "skip_until_at" => null]);
    }

    /**
     * @param int $is_self_employed
     * @throws Exception
     */
    public function updateIsSelfEmployed(int $is_self_employed) {
        $eshop = $this->getEshop();
        MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")->where("id", "=", $eshop->id)
            ->update(["is_self_employed" => $is_self_employed, "skip_until_at" => null]);
    }

    /**
     * @throws Exception
     */
    public function skip() {
        $eshop = $this->getEshop();
        $date = (new DateTimeHelper())->changeDays(+7)->mysqlFormat();
        MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")->where("id", "=", $eshop->id)
            ->update(["skip_until_at" => $date]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws Exception
     */
    private function getEshop() {
        if($this->eshop === null){
            $eshop = MDDatabaseConnections::getImportSupportConnection()
                ->table("project_ico")
                ->where("eshop_id", "=", $this->eshopId)->first();
            if ($eshop === null) {
                throw new Exception("Eshop does not exists");
            }
            $this->eshop = $eshop;
        }
        return $this->eshop;
    }




}