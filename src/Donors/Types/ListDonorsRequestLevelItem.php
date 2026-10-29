<?php

namespace Awardspring\Donors\Types;

enum ListDonorsRequestLevelItem: string
{
    case Annual = "Annual";
    case Mid = "Mid";
    case Major = "Major";
    case Principal = "Principal";
}
