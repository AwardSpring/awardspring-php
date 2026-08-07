<?php

namespace Awardspring\Gifts\Types;

enum CreateGiftV1RequestType: string
{
    case Gift = "gift";
    case Pledge = "pledge";
}
