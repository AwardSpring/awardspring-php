<?php

namespace Awardspring\Students\Types;

enum CreateStudentV1RequestIfEmailInUse: string
{
    case Error = "error";
    case AddRole = "add_role";
}
