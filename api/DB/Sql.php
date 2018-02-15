<?php
/**
 * User: dimasik142
 * User: ivanov.dmytro.ua@gmail.com
 * Date: 15.02.2018
 * Time: 13:06
 */

namespace Sql;


class Sql
{
    function __construct() {
        $this->connect = $this->sqlConnection();
    }

    /**
     * @var array
     */
    private $data = [
        'user' => 'root',
        'password' => 'root',
        'db' => 'first_step',
        'host' => 'localhost',
        'port' => 8889
    ];

    /**
     * @var object
     */
    private $connect;


    /**
     * @return object
     */
    protected function sqlConnection(){
        $link = mysqli_init();

        $connection = mysqli_real_connect(
            $link,
            $this->data['host'],
            $this->data['user'],
            $this->data['password'],
            $this->data['db'],
            $this->data['port']
        );
        if (!$connection) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        return $link;
    }

    /**
     * @param $string
     * @return bool|\mysqli_result
     */
    protected function querySql($string){
        return mysqli_query($this->connect, $string);
    }

    /**
     * @param $mode
     * @return bool
     */
    protected function setAutocommit($mode){
        return mysqli_autocommit($this->connect, $mode);
    }
}