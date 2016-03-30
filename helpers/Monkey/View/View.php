<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View;

use Exception;
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
    
    public function viewExists() {
        $viewPath = $this->getPathByName($this->template);
        return ViewFinder::existFile($viewPath);
    }
    
    public function render() {
        $latte = new Engine();
        $this->addMacros($latte);
        $latte->setTempDirectory(base_path('storage/framework/latte/'));
        
        
        $viewPath = $this->getPathByName($this->template);
        if( !$this->viewExists() ){
            throw new Exception("View not exists: {$viewPath}");
        }
        
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
    
    public function __toString() {
        return $this->render();
    }

    
    private function addMacros(Engine &$latte) {
        $set = new MacroSet($latte->getCompiler());
        
        $set->addMacro("isset", function($node, $writer) {
            $args = explode(',', $node->args);
            return $writer->write("echo Latte\Runtime\Filters::escapeHtml(isset($args[0]) ? $args[0] : '') ;");
        });
        
        $set->addMacro("view", function($node, $writer) {
            $args = explode(',', $node->args);
            if($args[0][0] == '$' ){
                return $writer->write("echo {$args[0]};");
            }else{
                if(isset($args[1])){
                    return $writer->write("echo new Monkey\View\View({$args[0]}, {$args[1]});");
                }else{
                    return $writer->write("echo new Monkey\View\View({$args[0]});");
                }
            }
        });
        
        $assetsArray = array('js', 'img', 'css');
        foreach ($assetsArray as $asset){
            $set->addMacro($asset, function($node, $writer) use ($asset){
                $args = explode(',', $node->args);
                return $writer->write("echo asset('assets/{$asset}/'.$args[0]);");
            });
        }
        
        
        
    }
}
