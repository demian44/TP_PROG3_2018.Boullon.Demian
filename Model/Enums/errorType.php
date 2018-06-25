<?php
/**
 * SI no hay error devolvemos este valor en cero.
 */
class REQUEST_ERROR_TYPE
{
    const NOERROR = 0;
    const DATABASE = 1;
    const DATATIPE = 2;
    const GENERAL = 3;
    const TOKEN = 4;
    const NODATA = 5;
    const NOEXIST = 6;
}
