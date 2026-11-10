<?php

namespace Awardspring\Types;

enum AddScholarshipQualificationV1RequestPlacement: string
{
    case EveryGroup = "every_group";
    case NewGroup = "new_group";
    case Group = "group";
}
