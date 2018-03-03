<?php
function ask($message)
{
	echo "$message: ";
	return trim(fgets(STDIN));
}