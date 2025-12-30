<?php


namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
/**
 * 
 */
class ConfirmPassword extends AbstractRule
{

	protected $new_password;
	protected $confirm_password;

	public function __construct($new_password, $confirm_password)
	{
      $this->new_password = $new_password;
      $this->confirm_password = $confirm_password;
	}
	
	public function validate($input)
	{
        
        if ($this->confirm_password == $this->new_password) {
              return true;
            } else {
              return false;
            }
	}
}









?>