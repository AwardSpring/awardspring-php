<?php

namespace Awardspring\Types;

enum ScholarshipQualificationRuleV1QuestionType: string
{
    case OneChoice = "one_choice";
    case MultipleChoice = "multiple_choice";
    case MultipleChoiceAny = "multiple_choice_any";
    case Major = "major";
    case Degree = "degree";
    case AcademicStatus = "academic_status";
    case Number = "number";
    case NumberRange = "number_range";
    case HighSchoolGpa = "high_school_gpa";
    case CollegeGpa = "college_gpa";
    case ExpenseRatio = "expense_ratio";
}
