<?php

namespace Awardspring\Donors\Types;

enum ListDonorsRequestSort: string
{
    case Name = "name";
    case LastContacted = "last_contacted";
}
