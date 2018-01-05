<?php
/**
 * Created by PhpStorm.
 * User: luke
 * Date: 7/01/16
 * Time: 12:04 AM
 */

/**
 * @Mincraft Library to execute commands to shell this is the only place shell exec will run from
 */
namespace Library;


class Minecraft
{
    /**
     * @var string
     */
    var $serverName = '';
    var $settings = '';
    public function Minecraft(Settings $settings){}
}