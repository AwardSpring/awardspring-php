<?php

namespace Awardspring\Donors\Types;

enum CreateDonorV1RequestRole: string
{
    case Individual = "Individual";
    case Organization = "Organization";
}
