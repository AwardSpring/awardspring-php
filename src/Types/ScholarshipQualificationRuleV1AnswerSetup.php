<?php

namespace Awardspring\Types;

enum ScholarshipQualificationRuleV1AnswerSetup: string
{
    case AnyOf = "any_of";
    case OnlyThisOne = "only_this_one";
}
