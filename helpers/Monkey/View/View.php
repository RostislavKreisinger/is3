<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View;

use Latte\Engine;
use Latte\Macros\MacroSet;


/**
 * Description of View
 *
 * @author Tomas
 */
class View extends BaseView {
    
    /**
     *
     * @var array setting.invoice.index => array('setting', 'invoice', 'index')
     */
    private $template = null;
    






    public function __construct($template = array(), $parameters = array()) {
        $this->setTemplate($template);
        $this->setParameters($parameters);
    }
    
    
    private function getPathByName($name) {
        return base_path('resources/views/').  implode('/', $name). '.latte';
    }
    
    public function render() {
        $latte = new Engine();
        $this->addMacros($latte);
        $latte->setTempDirectory(base_path('storage/framework/latte/'));
        
        if( !ViewFinder::existView($this->template) ){
            vde('view not exist');
            return '';
        }
        
        $viewPath = $this->getPathByName($this->template);
        $view = $latte->renderToString($viewPath, $this->getParametersToView());
        
        return $view;
    }
    
    public function getTemplate() {
        return $this->template;
    }

    public function setTemplate($template) {
        if(!is_array($template)){
            $template = explode('.', $template);
        }
        $this->template = $template;
        return $this;
    }

    
    private function addMacros(Engine &$latte) {
        $set = new MacroSet($latte->getCompiler());
        // $set->addMacro("isses", 'isset(%node.array[0])? %node.array[0] : "");');
        
        // $set->addMacro("isset", 'isset(%node.array[0])? %node.array[0] : (isset(%node.array[1])? %node.array[1]: "");');
        $set->addMacro("isset", function($node, $writer) {
            $args = explode(',', $node->args);
            return $writer->write("echo Latte\Runtime\Filters::escapeHtml(isset($args[0]) ? $args[0] : '') ;");
        });//'isset(%node.array[0])? %node.array[0] : (isset(%node.array[1])? %node.array[1]: "");');
    }
}
