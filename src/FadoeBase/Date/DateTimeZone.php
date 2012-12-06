<?php

namespace FadoeBase\Date;

class DateTimeZone extends \DateTimeZone
{

	public function __toString()
	{
		return $this->getName();
	}

}
