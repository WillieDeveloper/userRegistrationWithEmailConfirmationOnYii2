<?php
    class createModel extends model {
        
        //Создание таблиц
        public function createTables () {
            
            //Проверяем идентификатор БД
            if (!$this->dbLink) {
                return false;
            }

            //Если таблицы "CITIES" нет в БД, то создаём
            if (!$this->showTable ($this->dbLink, DB_NAME, TABLE_CITIES) ) {
                $tableCitiesCreateQuery = ('CREATE TABLE IF NOT EXISTS ' . TABLE_CITIES . ' (id_city INT(11) NOT NULL AUTO_INCREMENT,
                    name_city TINYTEXT DEFAULT NULL,
                    PRIMARY KEY (id_city))');
                
                //Если не запрос не выполнен, то Фолс
                if (!mysqli_query ($this->dbLink, $tableCitiesCreateQuery) ) {
                    unset($tableCitiesCreateQuery);
                    return false;
                }

                //Чистим память, загружаем города
                unset ($tableCitiesCreateQuery); 
                $this->getCitiesFromFile ($this->dbLink, CITIES_FILE);
            }

            //Если таблицы "USERS" нет в БД, то создаём
            if (!$this->showTable ($this->dbLink, DB_NAME, TABLE_USERS) ) {
                $tableUsersCreateQuery = ('CREATE TABLE IF NOT EXISTS ' . TABLE_USERS . ' (id_user INT(11) NOT NULL AUTO_INCREMENT,
                    name_user TINYTEXT DEFAULT NULL,
                    age_user TINYINT,
                    id_city INT(11) DEFAULT NULL,
                    PRIMARY KEY (id_user))');
                
                //Если не запрос не выполнен, то Фолс
                if (!mysqli_query ($this->dbLink, $tableUsersCreateQuery) ) {
                    unset ($tableUsersCreateQuery);
                    return false;
                }

                //Чистим память
                unset ($tableUsersCreateQuery);
            }

            return true;
        }
    }
?>