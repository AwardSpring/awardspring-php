<?php

namespace Awardspring\Donors\Types;

enum ListDonorsRequestGivingStatusItem: string
{
    case Recurring = "Recurring";
    case New_ = "New";
    case Lapsed = "Lapsed";
}
