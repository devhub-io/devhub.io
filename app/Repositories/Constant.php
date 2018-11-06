<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
