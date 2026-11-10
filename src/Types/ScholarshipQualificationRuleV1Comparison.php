<?php

namespace Awardspring\Types;

enum ScholarshipQualificationRuleV1Comparison: string
{
    case AtLeast = "at_least";
    case AtMost = "at_most";
    case MoreThan = "more_than";
    case LessThan = "less_than";
    case Equals = "equals";
    case Between = "between";
}
