<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Revisions
        DB::insert("CREATE TABLE `revisions` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `revisionable_type` VARCHAR(255) NOT NULL, `revisionable_id` INT(11) NOT NULL, `user_id` INT(11) DEFAULT NULL, `key` VARCHAR(255) NOT NULL, `old_value` TEXT , `new_value` TEXT , `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL)");

        // User
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@devhub.io',
            'password' => bcrypt('secret'),
        ]);

        // Category
        DB::insert("INSERT INTO `categories` (`id`, `title`, `slug`, `parent_id`, `created_at`, `updated_at`) VALUES (1, '程序语言', 'lang', 0, '2016-07-02 14:39:55', '2016-07-02 14:39:55')");
        DB::insert("INSERT INTO `categories` (`id`, `title`, `slug`, `parent_id`, `created_at`, `updated_at`) VALUES (2, 'Swift', 'swift', 1, '2016-07-02 14:39:55', '2016-07-02 14:39:55')");

        // Repos
        DB::insert("INSERT INTO `repos` (`id`, `title`, `category_id`, `slug`, `image`, `description`, `language`, `readme`, `homepage`, `github`, `stargazers_count`, `watchers_count`, `open_issues_count`, `forks_count`, `subscribers_count`, `issue_response`, `status`, `repos_created_at`, `repos_updated_at`, `fetched_at`, `created_at`, `updated_at`, `user_id`, `is_recommend`, `view_number`, `trends`, `owner`, `repo`, `analytics_at`, `cover`, `have_questions`, `document_url`) VALUES 
                    (1, 'swift', 2, 'apple-swift', 3, 'The Swift Programming Language', 'C++', '# swift', 'https://swift.org/', 'https://github.com/apple/swift', 35995, 35995, 123, 5255, 2422, 0, 1, '2017-01-16 01:42:53', '2017-01-11 07:46:49', '2017-01-11 08:11:31', '2016-07-02 14:27:44', '2017-01-16 06:42:53', 0, 0, 171, '0,15,50,63,0,35,0,53', 'apple', 'swift', '2016-12-09 08:15:57', 'https://avatars.githubusercontent.com/u/10639145?v=3', 1, 'https://swift.org/documentation/')");

        // Developer
        DB::insert("INSERT INTO `developer` (`id`, `login`, `name`, `github_id`, `avatar_url`, `html_url`, `type`, `site_admin`, `company`, `blog`, `location`, `email`, `public_repos`, `public_gists`, `followers`, `following`, `site_created_at`, `site_updated_at`, `created_at`, `updated_at`, `view_number`, `status`, `fetched_at`, `analytics_at`, `rating`) VALUES (1, '01org', '01org', 1635439, 'https://avatars.githubusercontent.com/u/1635439?v=3', 'https://github.com/01org', 'Organization', 0, '', 'https://01.org', 'Hillsboro, Oregon, USA', 'webmaster@linux.intel.com', 318, 0, 0, 0, '2012-04-12 04:26:38', '2016-10-11 10:10:55', '2016-10-11 15:21:40', '2017-01-13 04:08:58', 14, 1, '2016-10-11 15:21:40', '0000-00-00 00:00:00', 6855.97)");
    }
}
