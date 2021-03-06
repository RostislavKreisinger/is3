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
use Latte\PhpWriter;


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
        try{
            return $this->render();
        }catch(Exception $e){
            vde($e);
        }
    }

    
    private function addMacros(Engine &$latte) {
        $set = new MacroSet($latte->getCompiler());
        
        $set->addMacro("isset", function($node, $writer) {
            $args = explode(',', $node->args);
            return $writer->write("echo Latte\Runtime\Filters::escapeHtml(isset($args[0]) ? $args[0] : '') ;");
        });
        
        $set->addMacro("n", function($node, $writer) {
            $args = explode(',', $node->args);
            return $writer->write(  "if (is_null({$args[0]})) {
                                        echo '<span class=\'not-set-value\'>" . (isset($args[1])?$args[1]:'NULL') . "</span>';
                                    }else{
                                        echo Latte\Runtime\Filters::escapeHtml({$args[0]});
                                    }");
        });
        
        $set->addMacro("bool2text", function($node, $writer) {
            $args = explode(',', $node->args);
            return $writer->write(  "   \$value = (int) {$args[0]}; 
                                        echo '<span class=\'bool-to-text value-'.\$value.'\'>'.(\$value?'YES':'NO').'</span>';
                                    ");
        });
        
      
        
        $set->addMacro("view", function($node, $writer) {
            $args = explode(',', $node->args);
            if($args[0][0] == '$' ){
                return $writer->write("echo {$args[0]}->render();");
            }else{
                if(isset($args[1])){
                    return $writer->write("echo (new Monkey\View\View({$args[0]}, array_merge({$args[1]}, \$_parrams)))->render();");
                }else{
                    return $writer->write("echo (new Monkey\View\View({$args[0]}, \$_parrams))->render();");
                }
            }
        });
        
        $set->addMacro("varview", function($node, $writer) {
            $args = explode(',', $node->args);
            if($args[0][0] == '$' ){
                return $writer->write("echo {$args[0]}->render();");
            }else{
                if(isset($args[1])){
                    return $writer->write("echo (new Monkey\View\View({$args[0]}, {$args[1]}))->render();");
                }else{
                    return $writer->write("echo (new Monkey\View\View({$args[0]})->render();");
                }
            }
        });
        
        $assetsArray = array('assets'=> '', 'js'=>'js/', 'img' => 'images/', 'css' => 'css/');
        foreach ($assetsArray as $name => $asset){
            $set->addMacro($name, function($node, $writer) use ($asset){
                $args = explode(',', $node->args);
                return $writer->write("echo asset('assets/default/{$asset}'.$args[0]);");
            });
        }
        
        $set->addMacro("action", function($node, PhpWriter $writer) {
            $args = explode(',', $node->args);
            $secondParam = array();
            $add = false;
            foreach($args as $arg){
                if(trim($arg)[0] == '['){
                    if(count($secondParam) == 0 ){
                        $add = true;
                    }
                }
                if($add){
                    $secondParam[] = $arg;
                }
                
                if(trim($arg)[strlen(trim($arg))-1] == ']'){
                    $add = false;
                }
            }
            if(count($secondParam) == 0){
                if(isset($args[1])){
                    $secondParam = $args[1];
                }else{
                    $secondParam = '[]';
                }
            }else{
                $secondParam = implode(', ', $secondParam);
            }
            
            $write = "";
            $write .= "\$method = {$args[0]}; if(!strpos(\$method, '@')) { \$method .= '@getIndex'; } ";
            
            $write .= "echo action('App\\\\Http\\\\Controllers\\\\'.\$method, {$secondParam});";
            
            return $writer->write($write);
        });
        
        
        
    }
}
