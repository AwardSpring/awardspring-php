<?php

namespace Awardspring\Types;

enum DonorCustomFieldV1Kind: string
{
    case Text = "text";
    case Number = "number";
    case Date = "date";
    case SingleSelect = "single_select";
    case MultipleSelect = "multiple_select";
    case Other = "other";
}
