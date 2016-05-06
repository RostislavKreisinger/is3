<?php

namespace Monkey\ImportSupport\Resource\Button\Other;

use App\Http\Controllers\Button\Resource\Other\ShiftNextCheckDateButtonController;
use Monkey\ImportSupport\Resource\Button\BaseButton;
/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class ShiftNextCheckDateButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_DELETE,
                'shift_next_check_date',
                'Shift check date', 
                \Monkey\action(ShiftNextCheckDateButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        $this->setToNewWindow(false);
        $this->setTitle('Posune datum pristi kontroly za 7 dni.');
    }
    
}
