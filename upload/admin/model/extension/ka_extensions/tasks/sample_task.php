<?php
/*
	Sample task model
*/

// this namespace must be used for all tasks
namespace extension\ka_extensions\tasks;

// any task class consists of "Model" and "task class (similar to the file name)"
// other formats are not allowed
//
class ModelSampleTask extends \KaModel {

	/*
		$operation - operation name (string)
		$params    - array of parameters
		$stat      - array of returned strings for displaying results of the operation
		
		RETURNS:
			$return - text code
				'finished'      - operation is complete
				'not_finished'  - still working (additional calls needed)
	*/
	public function runSchedulerOperation($operation, $params, &$stat) {
			$return_code = 'finished';
			
			/* put your code below this line */		

			/* some statistics can be returned in the stat array */
			$stat = array(
				'Number of processed products' => 10,
			);
			
			return $return_code;
	}
}

// you have to specify a full class name alias for the short class name
//
class_alias(__NAMESPACE__ . '\ModelSampleTask', 'ModelExtensionKaExtensionsTasksSampleTask');