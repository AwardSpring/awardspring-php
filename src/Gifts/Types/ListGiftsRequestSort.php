<?php

namespace Awardspring\Gifts\Types;

enum ListGiftsRequestSort: string
{
    case DateDesc = "date_desc";
    case DateAsc = "date_asc";
}
