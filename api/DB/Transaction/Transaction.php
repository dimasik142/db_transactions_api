<?php
/**
 * User: dimasik142
 * User: ivanov.dmytro.ua@gmail.com
 * Date: 15.02.2018
 * Time: 13:43
 */

namespace Sql\Transaction;

use Sql\Sql;

class Transaction extends Sql
{
    function __destruct() {
        if ($this->transactionStatus){
            if ($this->transactionErrors){
                echo 'Під час транзакції виникла помилка - rollback. <br>';
                $this->transactionRollback();
            } else {
                echo 'Транзакція пройшла успішно - commit. <br>';
                $this->transactionCommit();
            }
        }
    }

    /**
     * @var array
     */
    protected $isolationLevels = [
        '0' => 'READ UNCOMMITTED', // Чтение неподтверждённых данных
        '1' => 'READ COMMITTED',   // Чтение подтверждённых данных
        '2' => 'REPEATABLE READ',  // Повторяемое чтение
        '3' => 'SERIALIZABLE',     // Сериализуемый
    ];

    /**
     * @var bool
     */
    private $transactionStatus = false;

    /**
     * @var bool
     */
    private $transactionErrors = false;

    /**
     * @param $isolationLevelNumber
     */
    public function startTransaction($isolationLevelNumber){
        $sqlQuery = $this->makeAddTransactionQueryString($isolationLevelNumber);
        $this->querySql($sqlQuery);
        $this->setAutocommit(FALSE);
        $this->transactionStatus = true;
    }

    public function finishTransaction(){
        if ($this->transactionStatus){
            if ($this->transactionErrors){
                echo 'Під час транзакції виникла помилка - rollback. <br>';
                $this->transactionRollback();
            } else {
                echo 'Транзакція пройшла успішно - commit. <br>';
                $this->transactionCommit();
            }
        }
        $this->transactionStatus = false;
        $this->transactionErrors = false;
    }

    /**
     * @param $isolationLevelNumber
     * @return string
     */
    private function getIsolationLevel($isolationLevelNumber){
        if (in_array($isolationLevelNumber,[0,1,2,3])) {
            return $this->isolationLevels[$isolationLevelNumber];
        } else {
            echo 'Рівня ізоляції з таким номером не існує. <br>';
        }
    }

    /**
     * @param $isolationLevelNumber
     * @return string
     */
    private function makeAddTransactionQueryString($isolationLevelNumber){
        $isolationLevelName = $this->getIsolationLevel($isolationLevelNumber);
        $queryResult = '';
        $queryResult .= 'SET AUTOCOMMIT = 0; ';
        $queryResult .= 'SET TRANSACTION ISOLATION LEVEL ';
        $queryResult .= $isolationLevelName . '; ';
        $queryResult .= 'START TRANSACTION; ';
        return $queryResult;
    }

    /**
     * @return bool|\mysqli_result
     */
    public function transactionCommit(){
        return $this->querySql("COMMIT;");
    }

    /**
     * @return bool|\mysqli_result
     */
    public function transactionRollback(){
        return $this->querySql("ROLLBACK;");
    }

    /**
     * @param $sqlQueryString
     */
    public function runQuery($sqlQueryString) {
        $result  = $this->querySql($sqlQueryString);
        if (!$result){
            echo 'Під час sql запиту відбулась помилка. ---  '. $sqlQueryString .'<br>';
            $this->transactionErrors = true;
        };
    }
}