<?php
/**
 * User: yuan
 * Date: 16/12/29
 * Time: 09:27
 */

namespace App\Repositories;


class Constant
{
    const DISABLE = 0;
    const ENABLE = 1;
    const DELETE = 2;

    const REPOS_URL_REGEX = "/https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)\\/([0-9a-zA-Z\\-\\.]*)/";
    const README_URL_REGEX = "/https?:\\/\\/github\\.com\\/[0-9a-zA-Z\\-\\.]*\\/[0-9a-zA-Z\\-\\.]*/";
    const DEVELOPER_URL_REGEX = "/^https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)$/";
}
