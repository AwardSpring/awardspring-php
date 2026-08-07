<?php

namespace Awardspring\DonorActivities\Types;

enum CreateDonorActivityV1RequestActivityType: string
{
    case LoggedEmail = "LoggedEmail";
    case LoggedPhone = "LoggedPhone";
    case LoggedMeeting = "LoggedMeeting";
    case LoggedNote = "LoggedNote";
    case LoggedPledge = "LoggedPledge";
}
