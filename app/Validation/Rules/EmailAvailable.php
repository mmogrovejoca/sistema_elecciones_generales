<?php


namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\AbstractRule;
/**
 * 
 */
class EmailAvailable extends AbstractRule
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
	
	public function validate($input)
	{
	 

		$has = $this->db->has('user', ["email" => $input]);
		if ($has === true) {
			return false;
		} elseif($has === false) {
			return true;
		}
		
		
	}
}









?>