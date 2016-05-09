<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button\Other;

use Monkey\ImportSupport\Resource\Button\BaseButton;

/**
 * Description of UnconnectButton
 *
 * @author Tomas
 */
class EshopDeleteDataButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        $project = \App\Model\Project::find($projectId);
        $clientId = $project->getUser()->getClient()->id;
        parent::__construct(
                BaseButton::BUTTON_TYPE_DELETE,
                'delete',
                'Delete data', 
                "https://import.monkeydata.com/importgoogle.monkeydata.cz/import-support-extra-save-functions/cleanEshopDataLoader.php?project_id={$projectId}&client_id={$clientId}"
                );
        $this->setToNewWindow(false);
        $this->setTitle('Smaze vsechna data a nastavi stazeni historie');
    }
}
