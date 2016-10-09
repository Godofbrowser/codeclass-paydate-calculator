<?php
namespace Paydate_calculator;
/*
*
** LET'S DEFINE OUR CLASS 'Paydate'
*
*/
class Paydate{
	protected $monthly_payday;
	protected $holidays;
	protected $initialPaydate;
	protected $paydate;
	public $date;
	public $weekday_str;
	
				function __construct($str){
						if($str > 28 || $str < 1) die("A VALID/REGULAR PAYDAY MUST BE BETWEEN 1 AND 28");

				   		$this->initialPaydate = date_create($str.date('-m-Y')); 
				   		$this->monthly_payday = $str; 
						$this->paydate = new \DAteTime($this->monthly_payday.$this->getInitialPaydate()->format('-m-Y')); 
						Self::addDayAndSetAttributes();

						if( $this->paydate->getTimestamp() <= time() ) $this->next();
				}

				function getInitialPaydate(){ return $this->initialPaydate; }

				function getPaydate(){ return $this->paydate; }

				function isWeekend(){ return in_array( $this->weekday_str , ['Sat','Sun'] ); }

				function IsHoliday(){ return in_array( $this->date , $this->holidays ) || Self::isWeekend(); }

				function increment(){ $this->addDayAndSetAttributes('1 day'); }

				function decrement(){ $this->addDayAndSetAttributes('-1 day'); }

				function next(){
					$this->initialPaydate->add( new \DateInterval('P1M') );
					$this->paydate = date_create($this->monthly_payday.$this->getInitialPaydate()->format('-m-Y'));
					Self::addDayAndSetAttributes();
				}

	protected function addDayAndSetAttributes($str = null) {	
						$this->paydate->add( date_interval_create_from_date_string($str) );
						$this->weekday_str = $this->paydate->format('D'); 
						$this->date = $this->paydate->format('d-m-Y'); 
				}

	public	function setHolidays($hol){ $this->holidays = $hol; }
} 
?>