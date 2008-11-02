<?php

define('TEST_TRUE', 1, false);
define('TEST_FALSE', 2, false);
define('TEST_EXCEPTION', 3, false);
define('TEST_VALUE', 4, false);


class Unit_Test {
	private $_tests = array();

	private $_types = array(
		1 => 'Truthy',
		2 => 'Falsy',
		3 => 'Exception',
		4 => 'Value'
	);
	
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	public function testTrue($true_value, $name) {
		$this->_tests[] = array(
			'name' => $name,
			'type' => TEST_TRUE,
			'value' => ( true === $true_value ? 'true' : 'false' ),
			'passed' => ( true === $true_value )
		);
	}

	public function testFalse($false_value, $name) {
		$this->_tests[] = array(
			'name' => $name,
			'type' => TEST_FALSE,
			'value' => ( false === $false_value ? 'false' : 'true' ),
			'passed' => ( false === $false_value )
		);
	}

	public function testException($method, $name) {
		$passed = true;
		try {
			eval($method . ';');
		} catch ( Exception $e ) {
			$value = $e->getMessage();
			$passed = false;
		}
		
		$this->_tests[] = array(
			'name' => $name,
			'type' => TEST_EXCEPTION,
			'value' => $value,
			'passed' => $passed
		);
	}
	
	public function testValue($expected, $actual, $name) {
		$this->_tests[] = array(
			'name' => $name,
			'type' => TEST_VALUE,
			'value' => $expected . ' equals ' . $actual,
			'passed' => ( $expected === $actual )
		);
	}
	
	public function showTests() {
		?>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr style="font-weight: bold;">
				<td width="20%">Test Type</td>
				<td width="30%">Test Name</td>
				<td width="50%">Test Value</td>
			</tr>
			<?php
			foreach ( $this->_tests as $test ) {
				?>
				<tr style="background-color: <?php echo ( true === $test['passed'] ? '#50DB56' : '#DB5050' ); ?>">
					<td><?php echo $this->_types[$test['type']]; ?></td>
					<td><?php echo $test['name']; ?></td>
					<td><?php echo $test['value']; ?></td>
				</tr>
				<?php
			}
		?>
		</table>
		<?php
	}
}

?>
