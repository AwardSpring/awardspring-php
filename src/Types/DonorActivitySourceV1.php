<?php

namespace Awardspring\Types;

enum DonorActivitySourceV1: string
{
    case Logged = "Logged";
    case Email = "Email";
    case Sms = "Sms";
    case Award = "Award";
    case GeneralApplication = "GeneralApplication";
}
