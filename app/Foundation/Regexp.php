<?php

namespace App\Foundation;

class Regexp
{
    // 正则表达式来自 https://github.com/VincentSit/ChinaMobilePhoneNumberRegex
    const PHONE = '^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7(?:[01356789]\d{2}|4(?:0\d|1[0-2]|9\d))|9[189]\d{2}|6[567]\d{2}|4(?:[14]0\d{3}|[68]\d{4}|[579]\d{2}))\d{6}$';
    const PASSWORD = '^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{6,18}$';

    const RESOURCE_ID = '^[1-9][0-9]*$';
}
