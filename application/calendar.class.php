<?php
/**
*@author  Xu Ding
*@email   thedilab@gmail.com
*@website http://www.StarTutorial.com
**/
class Calendar {  
	 
	 
	/********************* PROPERTY ********************/  
	private $dayLabels = array("Po","Wt","Śr","Cz","Pt","So","Ni");

	private $miesiac = array(1 => 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień');
	 
	private $currentYear=0;
	 
	private $currentMonth=0;
	 
	private $currentDay=0;
	 
	private $currentDate=null;
	 
	private $daysInMonth=0;
	 
	private $naviHref= null;

	private $poprzednie = true;
	 
	private $dane = Array();
	/**
	 * Constructor
	 */
	public function __construct($tmp){   
		$this->dane = $tmp;

	}
	/********************* PUBLIC **********************/  
		
	/**
	* print out the calendar
	*/
	public function show($data = null) {
		$year  = $data['rok'];
		 
		$month = $data['miesiac'];
		 
		if(null==$year&&isset($_GET['year'])){
 
			$year = $_GET['year'];
		 
		}else if(null==$year){
 
			$year = date("Y",time());  
		 
		}          
		 
		if(null==$month&&isset($_GET['month'])){
 
			$month = $_GET['month'];
		 
		}else if(null==$month){
 
			$month = date("m",time());
		 
		}             
		 
		$this->currentYear=$year;
		 
		$this->currentMonth=$month;
		 
		$this->daysInMonth=$this->_daysInMonth($month,$year);  
		 
		$content='<div id="calendar">'.
						'<div class="box">'.
						$this->_createNavi().
						'</div>'.
						'<div class="box-content">'.
								'<ul class="label">'.$this->_createLabels().'</ul>';   
								$content.='<div class="clear"></div>';     
								$content.='<ul class="dates">';    
								 
								$weeksInMonth = $this->_weeksInMonth($month,$year);
								// Create weeks in a month
								for( $i=0; $i<$weeksInMonth; $i++ ){
									 
									//Create days in a week
									for($j=1;$j<=7;$j++){
										$content.=$this->_showDay($i*7+$j);
									}
								}
								 
								$content.='</ul>';
								 
								$content.='<div class="clear"></div>';     
			 
						$content.='</div>';
				 
		$content.='</div>';
		return $content;   
	}
	 
	/********************* PRIVATE **********************/ 
	/**
	* create the li element for ul
	*/
	private function _showDay($cellNumber){
		 
		if($this->currentDay==0){
			 
			$firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
					 
			if(intval($cellNumber) == intval($firstDayOfTheWeek)){
				 
				$this->currentDay=1;
				 
			}
		}
		 
		if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
			 
			$this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
			 
			$cellContent = $this->currentDay;
			 
			$this->currentDay++;   
			 
		}else{
			 
			$this->currentDate =null;
 
			$cellContent=null;
		}
		
		
		if ($this->currentDate > date('Y-m-d')) { $this->poprzednie = false; }
		if ($this->poprzednie) {
			$klasa = 'zajete';
			$input = '';
		} else {
			$klasa = 'wolne';
			if ($cellContent) {
				$id = $this->currentYear.'-'.$this->currentMonth.'-'.$cellContent;
				$input = '<input id="'.$id.'" type="checkbox" class="koszt '.$this->currentYear.'-'.$this->currentMonth.'" value="'.CENA_ZA_BOKS_PRESTIZOWY_DZIEN.'" name="data['.$this->currentYear.']['.$this->currentMonth.']['.$cellContent.']" />';
			}
		}

		if ($this->dane[$this->currentYear][$this->currentMonth][$cellContent] == true) {
			$klasa = 'kupione';
			$input = '';
		}

		return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
				($cellContent==null?'mask':'').' '.$klasa.'"><label for="'.$id.'">'.$cellContent.'</label>'.$input.'</li>';
	}
	 
	/**
	* create navigation
	*/
	private function _createNavi(){
		 
		$nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
		 
		$nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
		 
		$preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
		 
		$preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
		 
		if ($this->currentMonth == date('m')) {
			$iloscDni = iloscDni(date('d-m-Y'), date('t-m-Y'));
			$cenaZaMiesiac = ceil($iloscDni / date('t') * CENA_ZA_BOKS_PRESTIZOWY_MIESIAC);
		} else 
			$cenaZaMiesiac = CENA_ZA_BOKS_PRESTIZOWY_MIESIAC;

		$id = $this->currentYear.'-'.$this->currentMonth;

	

		if (sizeof($this->dane[$this->currentYear][$this->currentMonth]) == 0) {
			$input = '<input type="checkbox" class="koszt" id="'.$id.'" data-miesiac="'.$id.'" value="'.$cenaZaMiesiac.'" name="data['.$this->currentYear.']['.$this->currentMonth.']" />';
		}

		return
			'<div class="header">'.
				//'<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
					'<label for="'.$id.'" class="title">'.$this->miesiac[(int)$this->currentMonth].' '.date('Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
					$input.
				//'<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
			'</div>';
	}
		 
	/**
	* create calendar week labels
	*/
	private function _createLabels(){  
				 
		$content='';
		 
		foreach($this->dayLabels as $index=>$label){
			 
			$content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
		}
		 
		return $content;
	}
	 
	 
	 
	/**
	* calculate number of weeks in a particular month
	*/
	private function _weeksInMonth($month=null,$year=null){
		 
		if( null==($year) ) {
			$year =  date("Y",time()); 
		}
		 
		if(null==($month)) {
			$month = date("m",time());
		}
		 
		// find number of days in this month
		$daysInMonths = $this->_daysInMonth($month,$year);
		 
		$numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
		 
		$monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
		 
		$monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
		 
		if($monthEndingDay<$monthStartDay){
			 
			$numOfweeks++;
		 
		}
		 
		return $numOfweeks;
	}
 
	/**
	* calculate number of days in a particular month
	*/
	private function _daysInMonth($month=null,$year=null){
		 
		if(null==($year))
			$year =  date("Y",time()); 
 
		if(null==($month))
			$month = date("m",time());
			 
		return date('t',strtotime($year.'-'.$month.'-01'));
	}
	 
}
