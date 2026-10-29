<?php

namespace Awardspring\Types;

enum DonorListItemV1GivingStatus: string
{
    case Recurring = "Recurring";
    case New_ = "New";
    case Lapsed = "Lapsed";
}
