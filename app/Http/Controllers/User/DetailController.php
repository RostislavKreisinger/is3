<?php



namespace App\Http\Controllers\User;

use App\Model\User;
use Monkey\Breadcrump\BreadcrumbItem;



/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    

    
    public function getIndex($userId) {
        $user = User::find($userId);
        $client = $user->getClient();
        $tariffOrders = $client->getTariffOrders();
        $tariff = $client->getTariff();
        $this->getView()->addParameter('user', $user);
        $this->getView()->addParameter('client', $client);
        $this->getView()->addParameter('tariff', $tariff);
        $this->getView()->addParameter('tariffOrders', $tariffOrders);
        
        $this->prepareMenu($user);
    }
    

    protected function breadcrumbBeforeAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbBeforeAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(self::class, ['user_id' => $parameters['user_id'] ])));
    }

}
