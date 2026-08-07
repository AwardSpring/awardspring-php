<?php

namespace Awardspring\Gifts\Types;

enum CreateGiftV1RequestGiftType: string
{
    case Cash = "Cash";
    case Check = "Check";
    case CreditOrDebit = "CreditOrDebit";
    case BankTransfer = "BankTransfer";
    case StockOrProperty = "StockOrProperty";
    case InKind = "InKind";
    case PayrollDeduction = "PayrollDeduction";
    case Online = "Online";
    case Other = "Other";
}
