<?php
App::import('Core', 'CakeTestCase');
App::import('Core', 'Dispatcher');

class ExtendedTestCase extends CakeTestCase{
	
	
	/**
	 * pass in arrays or nested arrays to compare against.
	 * assertIsSubset will only check keys in $subset against keys in $set. 
	 * any values within $set that are not within $subset will be ignored.
	 * 
	 */	
	
	function assertIsSubset($subset=array(), $set=array()){
		$check = $this->assertIsSubsetWrapped($subset,$set);
		if($check['match'] == true){
			return $this->assertEqual(1,1);
		}else{
			if(!empty($check['errorMsg'])){
				try {
				    throw new Exception( $check['errorMsg']);
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				  // return $this->assertEqual(1,$check['errorMsg']);
				}
				
			}
			
			$matcherEvalString = '';
			foreach($check['keyTrail'] as $key){
				$matcherEvalString.="['$key']";
			}
			
			
			eval('$first'.$matcherEvalString.' = "'. $check['errorVals']['first'].'";' );
			eval('$second'.$matcherEvalString.' = "'. $check['errorVals']['second'].'";' );
			
			return $this->assert(
            	new EqualExpectation($first),
            	$second //, $message
            );
		}
		
	}
	
	/**
	 * wrapped function used for testing
	 */
	
	function assertIsSubsetWrapped($subset, $set){
		$check = false;
		if(is_array($subset)){
			if(!is_array($set)){
				$msg = "assertIsSubset argument 2 should be an array";
				return array('match' => false, 'errorMsg' => $msg);
			}
		}else{
			$msg = "assertIsSubset argument 1 should be an array";
			return array('match' => false, 'errorMsg' => $msg);
		}
		return $this->checkSubset($subset,$set);
		
	}

	/**
	 * 
	 */
	
	function checkSubset($subset=array(), $set=array(), $keyTrail=array(), $iteration = 0){
		foreach ($subset as $key=>$val){
			if(is_array($val)){
		
				$keyTrail[$iteration] = $key;
				$iteration++;
				$rdata = $this->checkSubset($val,$set[$key],$keyTrail,$iteration);
				if($rdata['match'] == false){
					return array(
						'keyTrail'=>$rdata['keyTrail'],'match'=>false, 'errorVals' => $rdata['errorVals']);
				}
			}else{
		
				$keyTrail[$iteration] = $key;
				if($val != $set[$key] || !array_key_exists($key, $set)){
		
					$errorVals = array('first' => $val, 'second' =>$set[$key]);
					return array('keyTrail'=>$keyTrail,'match'=>false, 'errorVals' => $errorVals);
				}	
			}	
		}
		return array('match'=>true);
	}	
	
}