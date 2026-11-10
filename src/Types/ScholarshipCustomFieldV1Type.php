<?php

namespace Awardspring\Types;

enum ScholarshipCustomFieldV1Type: string
{
    case Text = "text";
    case Number = "number";
    case OneChoice = "one_choice";
    case MultipleChoice = "multiple_choice";
}
