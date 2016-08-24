<?php
namespace Paydate_calculator;
/*
*
* This is actually my first OOP mini application. 
* Thanks to codeclasss for this line('The initial paydate given to your class should not be adjusted, even if it falls on a weekend or a holiday') 
* which inspired me to give OOP a trial rather than the procedural way.
* NOW LET'S DEFINE OUR CLASS 'Paydate'
*
*/
class Paydate{
	protected $monthly_payday; //int $monthly_payday defines the numerical value of the monthly pay day
	protected $initialPaydate;
	public $dateOBJ; //useful for increment/decrement without affecting out InitialPaydate position
	
				function __construct($str){
					
					//First things first
					//we know not all months have 29, 30, or 31 days.
					($str > 28 || $str < 1) ? die("A VALID/REGULAR PAYDAY MUS BE BETWEEN 1 AND 28") : '';
					
				   try{ $this->initialPaydate = date_create($str.date('-m-Y')); $this->monthly_payday = $str; }catch(Exception $e){ die($e->getMessage());}
				   //try{ $this->initialPaydate = date_create($str.'-01-2016'); $this->monthly_payday = $str; }catch(Exception $e){ die($e->getMessage());}

					try{ $this->dateOBJ = date_create($this->monthly_payday.$this->getInitialPaydate()->format('-m-Y')); }catch(Exception $e){ die($e->getMessage());}
					$this->weekday_str = $this->dateOBJ->format('D'); //useful for checking if is_weekend
					$this->date = $this->dateOBJ->format('d-m-Y'); //useful for checking if is_holiday
					
					//this ensures we don't output today or a past day in the starting month, by moving to the next month
					( strtotime($this->dateOBJ->format('d-m-Y')) <= strtotime(date('d-m-Y')) ) ? $this->next() : '';
				}
				
				function increment(){ $this->addDate('1 day');} //this is useful for weekends
				function decrement(){$this->addDate('-1 day');} //this is useful for holidays
	protected function addDate($str) {	
						date_add($this->dateOBJ, date_interval_create_from_date_string($str));
						$this->weekday_str = $this->dateOBJ->format('D'); 
						$this->date = $this->dateOBJ->format('d-m-Y'); 
						$this->date_2 = $this->dateOBJ->format('d-M-Y'); 
					}
					
				//this keeps us iterating to the next month
				function next(){	
					date_add($this->initialPaydate, date_interval_create_from_date_string('1 month'));
					$this->dateOBJ = date_create($this->monthly_payday.$this->getInitialPaydate()->format('-m-Y'));
					$this->addDate('0 day');
				}
				function getInitialPaydate(){ return $this->initialPaydate; }
}
?>