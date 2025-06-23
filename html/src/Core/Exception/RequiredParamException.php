<?php

namespace App\Core\Exception;

class RequiredParamException extends AppException {
	public function __construct() {
        $message = 'Required param is empty, please check documentation';
        $code = 400;
		parent::__construct($message, $code, null);
	}

}