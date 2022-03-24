<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Shortlink object for manipulating data
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_shortlink;

defined('MOODLE_INTERNAL') || die();


class Shortlink
{

    private $courseId;

    function __construct(int $courseId = 0){
        $this->courseId = $courseId;
    }

    /**
     * @param int $user ID User yang mengenerate shortlink (optinal)
     * @return array
     * @throws \dml_exception
     *
     * Fungsi ini digunakan untuk mengambil data shortlink yang telah digenerate
     */
    function getShortLinks(int $user=0){
        global $DB;
        $data = array("course" => $this->courseId);

        if ($user != 0){
            $data["user"] = $user;
        }

        return $DB->get_records_sql(
            "SELECT A.*,
                       IFNULL(B.total_click, 0) total_click
                FROM {local_shortlink_link} A
                LEFT JOIN (
                    SELECT link, COUNT(1) as total_click
                    FROM {local_shortlink_link_visited}
                    GROUP BY 1
                ) B ON A.id = B.link
                WHERE
                    A.course = :course
                ORDER BY A.timecreated DESC",
            array("course" => $this->courseId)
        );
    }

    /**
     * Fungsi untuk menyimpan data shortlink
     * @param string $link Short link yang digenerate oleh user
     * @param int $user user yang mengenerate shortlink
     * @param int $timecreated waktu generate shortlink
     * @return void
     */
    function createShortLink(string $link, int $user, int $timecreated){
        global $DB;

        $data = array(
            "course" => $this->courseId,
            "user" => $user,
            "link" => $link,
            "timecreated" => $timecreated
        );

        $DB->insert_record('local_shortlink_link', $data);
    }

    /**
     * Fungsi untuk menghapus shortlink
     * @param int $id id shortlink
     * @return void
     * @throws \dml_exception
     */
    function deleteShortLink(int $id){
        global $DB;

        $DB->delete_records('local_shortlink_link_visited', array("link" => $id));
        $DB->delete_records('local_shortlink_link', array("id" => $id));
    }

    /**
     * Fungsi untuk mendapatkan data course berdasarkan shortlink
     * @param string $link
     * @return false|mixed|\stdClass
     * @throws \dml_exception
     */
    function getShortlink(string $link){
        global $DB;

        $shortlink = $DB->get_record('local_shortlink_link', array("link" => $link), '*', MUST_EXIST);

        return $shortlink;
    }

    /**
     * Fungsi untuk insert visited link
     * @param $link objek link yang dikunjungi
     * @param $user user yang mengunjungi link
     * @return void
     * @throws \dml_exception
     */
    function visited($link, $user){
        global $DB;
        $data = array(
            "user" => $user,
            "link" => $link->id,
            "timecreated" => time()
        );

        $DB->insert_record("local_shortlink_link_visited", $data);
    }
}