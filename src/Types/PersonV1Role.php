<?php

namespace Awardspring\Types;

enum PersonV1Role: string
{
    case SuperAdministrator = "super_administrator";
    case Administrator = "administrator";
    case DepartmentAdmin = "department_admin";
    case Reviewer = "reviewer";
    case Student = "student";
    case Recommender = "recommender";
    case Donor = "donor";
    case Organization = "organization";
    case ClientServiceAdmin = "client_service_admin";
    case AwardspringStaff = "awardspring_staff";
}
