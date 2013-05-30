<?php
/**
 * Error template for JSON request
 *
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Wan Qi Chen <kami@kamisama.me>
 * @copyright     Copyright 2012, Wan Qi Chen <kami@kamisama.me>
 * @link          http://resqueboard.kamisama.me
 * @package       resqueboard
 * @subpackage	  resqueboard.template
 * @since         2.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo json_encode(array('message' => $message));
