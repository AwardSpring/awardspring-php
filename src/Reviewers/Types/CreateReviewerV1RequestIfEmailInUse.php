<?php

namespace Awardspring\Reviewers\Types;

enum CreateReviewerV1RequestIfEmailInUse: string
{
    case Error = "error";
    case AddRole = "add_role";
}
