<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author Martin
 */
class controllers_index extends Controller{

    function indexAction(){
		$URL = explode("/", $_SERVER['REQUEST_URI']);
		$plugin = (!empty($URL[3]))? $URL[3]:"content";
		$method = (!empty($URL[4]))? $URL[4]."Action": "indexAction";
		$obj = Lightweight::Plugin($plugin);
		$obj->$method();
    }
}
?>
