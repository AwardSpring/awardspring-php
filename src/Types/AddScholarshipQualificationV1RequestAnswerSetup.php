<?php

namespace Awardspring\Types;

enum AddScholarshipQualificationV1RequestAnswerSetup: string
{
    case AnyOf = "any_of";
    case OnlyThisOne = "only_this_one";
}
