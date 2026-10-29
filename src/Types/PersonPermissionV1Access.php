<?php

namespace Awardspring\Types;

enum PersonPermissionV1Access: string
{
    case None = "none";
    case View = "view";
    case Edit = "edit";
    case No = "no";
    case Yes = "yes";
}
